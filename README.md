# FIAP Hackathon - Monolith

Repositório responsável pela aplicação monólita, cuja é responsável pela camada HTTP do projeto. Expõe endpoints de API para cadastro e login de usuário, bem como cadastro, consulta e download de vídeos.
Se comunica com a aplicação worker por meio de mensageria, postando uma mensagem em determinado tópico do Pub/Sub, cuja é recebida pela aplicação worker.
Também contém a infraestrutura específica dessa aplicação (Cloud Run e recursos auxiliares) (para maiores informações, consultar a pasta [terraform](./terraform))

## Como utilizar

1. Clone o repositório
2. Construa e suba os containeres (`docker compose up -d`)
3. A aplicação estará disponível na porta 80
