# Sample Sqlcommenter Hyperf POC

Esse é um projeto que tem o principal objetivo validar as configurações da biblioteca do [sqlcommenter-hyperf](https://github.com/ReinanHS/sqlcommenter-hyperf). Além disso, alguns testes de performance são feitos dentro desse projeto para verificarmos o desempenho dessa ferramenta.

## Configuração inicial do projeto

Para configurar o projeto na sua máquina, siga as etapas abaixo:

### Passo 1: Iniciar o container

Execute o comando abaixo para iniciar o container:

```sh
make up
```

### Passo 2: Acessar o container

Execute o comando abaixo para acessar o container:

```sh
make php
```

### Passo 3: Baixar as dependências e executar os testes

Execute os comandos abaixo para instalar as dependências e rodar os testes:

```sh
composer install
php bin/hyperf.php migrate 
composer test
```

## Configuração do projeto na Google Cloud

Siga as instruções abaixo para testar esse projeto dentro da Google Cloud.

### Passo 1: Configurar o ID do projeto

Primeiro, defina a variável de ambiente com o ID do seu projeto na Google Cloud:

```sh
export DEVSHELL_PROJECT_ID=id-do-projeto
```

### Passo 2: Criar a instância do Cloud SQL

Crie uma instância do Cloud SQL com as seguintes configurações:

```sh
gcloud sql instances create sample-sqlcommenter-database \
    --project=$DEVSHELL_PROJECT_ID \
    --database-version=MYSQL_8_0_31 \
    --tier=db-custom-1-3840 \
    --region=us-central1 \
    --activation-policy=ALWAYS \
    --availability-type=ZONAL \
    --storage-size=10GB \
    --storage-type=SSD \
    --storage-auto-increase \
    --no-backup \
    --maintenance-window-day=SUN \
    --maintenance-window-hour=13 \
    --maintenance-release-channel=preview \
    --network=projects/$DEVSHELL_PROJECT_ID/global/networks/default \
    --no-assign-ip \
    --insights-config-query-insights-enabled \
    --insights-config-record-application-tags \
    --insights-config-record-client-address \
    --no-deletion-protection \
    --edition=ENTERPRISE
```

### Passo 3: Importar banco de dados

Importe o banco de dados para a instância criada:

```sh
gcloud sql import sql sample-sqlcommenter-database gs://$DEVSHELL_PROJECT_ID/sample-sqlcommenter-database.sql
```

O banco de dados completo utilizado para a realização dos testes pode ser encontrado no site oficial do MySQL:

- [MySQL: Employee database](https://dev.mysql.com/doc/index-other.html)

### Passo 4: Criar usuário para o banco de dados

Crie um usuário para o banco de dados:

```sh
gcloud sql users create demo \
--instance=sample-sqlcommenter-database \
--password=secret
```

### Passo 5: Definir variáveis de ambiente para conexão com o banco de dados

Obtenha o endereço privado da instância do banco de dados e defina as variáveis de ambiente necessárias:

```sh
export DB_HOST=$(gcloud sql instances list --filter=name:sample-sqlcommenter-database --format="value(PRIVATE_ADDRESS)") 
export DB_DATABASE=employees 
export DB_USERNAME=demo 
export DB_PASSWORD=secret
```

### Passo 6: Implantar aplicação no Cloud Run

Implante sua aplicação no Cloud Run com as seguintes configurações:

```sh
gcloud run deploy sample-sqlcommenter-hyperf-poc \
--image=reinanhs/sample-sqlcommenter-hyperf-poc:benchmarking \
--allow-unauthenticated \
--memory=1Gi \
--min-instances=1 \
--max-instances=1 \
--set-env-vars=DB_HOST=$DB_HOST \
--set-env-vars=DB_DATABASE=$DB_DATABASE \
--set-env-vars=DB_USERNAME=$DB_USERNAME \
--set-env-vars=DB_PASSWORD=$DB_PASSWORD \
--no-cpu-throttling \
--region=us-central1 \
--network=default \
--subnet=default \
--vpc-egress=private-ranges-only \
--project=$DEVSHELL_PROJECT_ID
```

### Passo 7: Testar a aplicação

Para testar a aplicação, execute o comando abaixo e obtenha a URL do serviço:

```sh
curl $(gcloud run services describe sample-sqlcommenter-hyperf-poc --platform managed --region=us-central1 --format 'value(status.url)')
```

### Passo 8: Limpeza dos recursos

Para deletar os serviços e a instância do banco de dados criados, execute os seguintes comandos:

```sh
gcloud run services delete sample-sqlcommenter-hyperf-poc --region=us-central1
gcloud sql instances delete sample-sqlcommenter-database
```