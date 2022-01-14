# **Dashboard bancário backend**

Backend feito em Laravel para conectar-se com o frontend feito em react.

-- --

## **Features**

- Cadastro e login de usuário com geração automática de número de uma conta
- Mostrar saldo do usuário
- Depósito e Saque
- Mostrar todas as transações realizadas do usuário
- Filtrar transações entre duas datas
- Mostrar histórico de saldo calculando os Depósitos e Saques realizados do dia
- Deletar alguma linha do histórico de transações, (saldo não é alterado).

-- --

## **Getting Started**

### Abra o Prompt de Comando

    git clone https://github.com/MatheusGomesRocha/dashboard_backend
    cd dashboard_backend && php artisan serve

    git clone https://github.com/MatheusGomesRocha/dashboard
    cd dashboard && npm install

### Para o backend abra um novo Prompt de Comando

    Tenha PHP e Composer configurados na sua variável global PATH

    cd path/dashboard_backend

    composer install --no-scripts

    cp .env.example .env

    php artisan key:generate
    
    php artisan migrate --seed
    
    npm install

    php artisan serve
    

### Para o frontend abra um novo Prompt de Comando

    cd path/dashboard && npm start
    
-- --

## **Tecnologia usada**

- Laravel e MySQL

-- --
## Meta

- Matheus Gomes
- Email - matheusgomes192@hotmail.com
- Linkedin - https://www.linkedin.com/in/matheus-gomes-2a61a8190/ 
