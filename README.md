# Request Logger App

## Popis aplikácie

Táto aplikácia zapisuje každý request do log súboru.

Pri každom refreshnutí stránky sa vytvorí nový záznam obsahujúci:
- čas requestu
- IP adresu
- HTTP metódu

Logy sa ukladajú do perzistentného Kubernetes úložiska.

---

## Build Docker image

```bash
docker build -t dawekka/exam-app:latest .
```

## Push image na DockerHub

```bash
docker push dawekka/exam-app:latest
```

## Nasadenie do Kubernetes

```bash
kubectl apply -f config/k8s/
```

## Kontrola komponentov

```bash
kubectl get all -n exam-david-karacsony

kubectl get pvc -n exam-david-karacsony

kubectl get configmap -n exam-david-karacsony
```

## Testovanie aplikácie

Aplikácia bude dostupná na:

```text
http://localhost:30082
```

Po refreshnutí stránky:
- sa vytvorí nový log
- logy sa zobrazia na stránke

## Použité Kubernetes komponenty

- Namespace
- ConfigMap
- PersistentVolume
- PersistentVolumeClaim
- Deployment
- InitContainer
- Service typu NodePort