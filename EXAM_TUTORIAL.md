# Kubernetes Exam Quick Tutorial

## 1. Copy Prepared Project

```bash
cd ~

cp -r vkvs2-exam-request-logger exam-david-karacsony

cd exam-david-karacsony

code .
```

---

## 2. Things To Change On Exam Day

### Namespace

Current:

```yaml
name: exam-david-karacsony
```

Check all occurrences:

```bash
grep -R "exam-david-karacsony" config/k8s
```

---

### Docker Image

Current:

```yaml
image: dawekka/exam-app:latest
```

Possible change:

```yaml
image: dawekka/exam-david-karacsony:latest
```

Use same name in Docker commands.

---

### NodePort

Current:

```yaml
nodePort: 30082
```

If already used:
- 30080
- 30081
- 30082
- 30083
- 30084

---

## 3. Build Docker Image

```bash
docker build -t dawekka/exam-app:latest .
```

Check image:

```bash
docker images | grep exam-app
```

---

## 4. Test Docker Container

```bash
docker run --name exam-app-test -p 8080:80 dawekka/exam-app:latest
```

Open:

```text
http://localhost:8080
```

Refresh page multiple times and verify logs appear.

Stop/remove container:

```bash
docker stop exam-app-test

docker rm exam-app-test
```

If container already exists:

```bash
docker rm -f exam-app-test
```

---

## 5. Push To DockerHub

```bash
docker login

docker push dawekka/exam-app:latest
```

Kubernetes runs Docker images, not source code directly.

---

## 6. Apply Kubernetes Files

Recommended order:

```bash
kubectl apply -f config/k8s/namespace.yaml

kubectl apply -f config/k8s/pv.yaml

kubectl apply -f config/k8s/configmap.yaml

kubectl apply -f config/k8s/pvc.yaml

kubectl apply -f config/k8s/deployment.yaml

kubectl apply -f config/k8s/service.yaml
```

---

## 7. Kubernetes Checks

```bash
kubectl get all -n exam-david-karacsony

kubectl get pods -n exam-david-karacsony

kubectl get svc -n exam-david-karacsony

kubectl get pvc -n exam-david-karacsony

kubectl get pv

kubectl get configmap -n exam-david-karacsony
```

PVC should be:

```text
Bound
```

---

## 8. Browser Test

Open:

```text
http://localhost:30082
```

Refresh page multiple times and verify:
- logs are created
- logs are displayed on page

---

## 9. Logs + Debugging

Deployment logs:

```bash
kubectl logs -n exam-david-karacsony deployment/exam-app
```

InitContainer logs:

```bash
kubectl logs -n exam-david-karacsony deployment/exam-app -c prepare-storage
```

Describe pod:

```bash
kubectl describe pod POD_NAME -n exam-david-karacsony
```

---

## 10. Exec Into Pod

Get pod name:

```bash
kubectl get pods -n exam-david-karacsony
```

Enter pod:

```bash
kubectl exec -it POD_NAME -n exam-david-karacsony -c exam-app-container -- sh
```

Inside container:

```bash
ls -la /app

ls -la /app/logs

cat /app/logs/requests.log
```

---

## 11. Persistence Test

Restart deployment:

```bash
kubectl rollout restart deployment/exam-app -n exam-david-karacsony
```

Check rollout:

```bash
kubectl rollout status deployment/exam-app -n exam-david-karacsony
```

Logs should remain saved.

---

## 12. Delete Pod Test

```bash
kubectl delete pod POD_NAME -n exam-david-karacsony
```

Deployment should create new pod automatically.

---

## 13. Common Problems

### Namespace not found

Cause:
- files applied in wrong order

Fix:
- apply individually in correct order

---

### NodePort already allocated

Change:

```yaml
nodePort: 30082
```

to another value.

Reapply:

```bash
kubectl apply -f config/k8s/service.yaml
```

---

### ImagePullBackOff

Cause:
- image not pushed to DockerHub

Fix:

```bash
docker push dawekka/exam-app:latest
```

Restart deployment:

```bash
kubectl rollout restart deployment/exam-app -n exam-david-karacsony
```

---

## 14. Cleanup

Remove Kubernetes resources:

```bash
kubectl delete -f config/k8s/
```

If namespace still exists:

```bash
kubectl delete namespace exam-david-karacsony
```

If PV still exists:

```bash
kubectl delete pv exam-app-pv
```

Check cleanup:

```bash
kubectl get all -n exam-david-karacsony

kubectl get pv
```

Optional Docker cleanup:

```bash
docker rm -f exam-app-test
```

## 15. Quick Exam Order

```bash
docker build -t dawekka/exam-app:latest .

docker login

docker push dawekka/exam-app:latest

kubectl apply -f config/k8s/namespace.yaml

kubectl apply -f config/k8s/pv.yaml

kubectl apply -f config/k8s/configmap.yaml

kubectl apply -f config/k8s/pvc.yaml

kubectl apply -f config/k8s/deployment.yaml

kubectl apply -f config/k8s/service.yaml

kubectl get all -n exam-david-karacsony
```