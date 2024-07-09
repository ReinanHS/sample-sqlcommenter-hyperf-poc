# Configuração do OpenTelemetry Collector Contrib

Este guia fornece uma visão passo a passo para configurar o OpenTelemetry Collector no Google Cloud usando um ambiente de teste para esse projeto. 
Seguindo as instruções abaixo, você será capaz de configurar um cluster Kubernetes no GKE, criar uma conta de serviço, atribuir permissões necessárias e implantar o OpenTelemetry Collector.

## Passo 1: Criar um Cluster Kubernetes no GKE

Use o comando abaixo para criar um cluster Kubernetes. Este cluster será utilizado para hospedar o OpenTelemetry Collector.

```shell
gcloud beta container --project ${DEVSHELL_PROJECT_ID} clusters create "poc-sqlcommenter-autopilot-cluster-1" \
--region "us-central1" \
--no-enable-basic-auth \
--cluster-version "1.29.4-gke.1043004" \
--release-channel "regular" \
--machine-type "e2-custom-2-1024" \
--image-type "COS_CONTAINERD" \
--disk-type "pd-balanced" \
--disk-size "10" \
--metadata disable-legacy-endpoints=true \
--scopes "https://www.googleapis.com/auth/devstorage.read_only","https://www.googleapis.com/auth/logging.write","https://www.googleapis.com/auth/monitoring","https://www.googleapis.com/auth/servicecontrol","https://www.googleapis.com/auth/service.management.readonly","https://www.googleapis.com/auth/trace.append" \
--spot \
--num-nodes "1" \
--logging=SYSTEM,WORKLOAD \
--monitoring=SYSTEM \
--enable-ip-alias \
--network "projects/${DEVSHELL_PROJECT_ID}/global/networks/default" \
--subnetwork "projects/${DEVSHELL_PROJECT_ID}/regions/us-central1/subnetworks/default" \
--no-enable-intra-node-visibility \
--default-max-pods-per-node "110" \
--security-posture=standard \
--workload-vulnerability-scanning=disabled \
--no-enable-master-authorized-networks \
--addons HorizontalPodAutoscaling,HttpLoadBalancing,GcePersistentDiskCsiDriver \
--enable-autoupgrade \
--enable-autorepair \
--max-surge-upgrade 1 \
--max-unavailable-upgrade 0 \
--binauthz-evaluation-mode=DISABLED \
--enable-managed-prometheus \
--enable-shielded-nodes
```

## Passo 2: Criar uma conta de serviço para o OpenTelemetry Collector

Crie uma conta de serviço no IAM que será usada pelo OpenTelemetry Collector para se autenticar com os serviços do Google Cloud.

```shell
gcloud iam service-accounts create opentelemetry-collector --display-name="OpenTelemetry Collector Service Account"
```

## Passo 3: Atribuir papéis à conta de serviço

Atribua os papéis necessários à conta de serviço criada para permitir que ela envie dados de rastreamento e monitoração para o Google Cloud.

```shell
gcloud projects add-iam-policy-binding ${DEVSHELL_PROJECT_ID} \
--member=serviceAccount:opentelemetry-collector@$DEVSHELL_PROJECT_ID.iam.gserviceaccount.com \
--role=roles/cloudtrace.agent
```

```shell
gcloud projects add-iam-policy-binding ${DEVSHELL_PROJECT_ID} \
--member=serviceAccount:opentelemetry-collector@$DEVSHELL_PROJECT_ID.iam.gserviceaccount.com \
--role=roles/monitoring.viewer
```

## Passo 4: Implantar o OpenTelemetry Collector no Kubernetes

Aplique o arquivo de configuração do OpenTelemetry Collector no cluster Kubernetes.

```shell
kubectl apply -f .k8s/otel-collector.yaml
```

Ou

```shell
kubectl apply -f https://raw.githubusercontent.com/ReinanHS/sample-sqlcommenter-hyperf-poc/main/.k8s/otel-collector.yaml
```

## Passo 5: Configurar a política do IAM para a conta de serviço do Kubernetes

Crie uma política do IAM que permita que a ServiceAccount do Kubernetes personifique a conta de serviço do IAM.

```shell
gcloud iam service-accounts add-iam-policy-binding opentelemetry-collector@$DEVSHELL_PROJECT_ID.iam.gserviceaccount.com \
--role roles/iam.workloadIdentityUser \
--member "serviceAccount:$DEVSHELL_PROJECT_ID.svc.id.goog[default/opentelemetry-collector-ksa]"
```

## Passo 6: Anotar a ServiceAccount do Kubernetes

Anote a ServiceAccount do Kubernetes para estabelecer a associação entre a conta de serviço do Kubernetes e a conta de serviço do IAM.

```shell
kubectl annotate serviceaccount opentelemetry-collector-ksa \
--namespace default \
iam.gke.io/gcp-service-account=opentelemetry-collector@$DEVSHELL_PROJECT_ID.iam.gserviceaccount.com
```

Seguindo esses passos, você terá configurado corretamente o OpenTelemetry Collector no Google Cloud, permitindo a coleta e monitoramento eficaz dos dados de observabilidade em seu ambiente de teste.