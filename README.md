# Trabalho G2: API de Gerenciamento de Livros

**Nanodegree:** Arquitetura de Hardware  
**Disciplina:** Sistemas Operacionais  
**Semestre:** 2025.1  
**Data de entrega:** 22/05/2025  
**Professor:** M.Sc. Fernando P. Pinheiro  
**E-mail:** fernando.pinheiro@atitus.edu.br  

---

## Integrantes  
- João Pedro Rodrigues  
- Ricardo Basso Gunther  
- Ricardo da Silva Groth  
- Nycolas Musskopf Fachi  
- Fabricio Panisson  
- Jean de Cesare  

---

## Descrição do Projeto  
API RESTful de gerenciamento de livros, implementada com Slim Framework e organizada segundo a Arquitetura Hexagonal (Ports & Adapters). O projeto está dividido em camadas de domínio, aplicação, infraestrutura e interface HTTP, rodando em contêineres Docker.

---

## Arquitetura Hexagonal  
- **Domain**: Entities e Ports (interfaces do repositório)  
- **Application**: Use Cases (regras de negócio)  
- **Infrastructure**: Adapters (persistência via PDO/MySQL)  
- **Interfaces**: HTTP Controllers e rotas Slim  
- **config**: Configurações de ambiente  
- **public**: Entry point web  

---

## Como Executar do Zero  
1. Copie `.env.example` para `.env` e ajuste se necessário.  
2. Limpe containers, rede e volumes antigos:  
   ```bash
   docker compose down --volumes
   ```  
3. Construa e suba os serviços em modo destacado:  
   ```bash
   docker compose up --build -d
   ```  
4. Acesse a API em `http://localhost:8081/books`.  
