# 🎓 Guia Completo do Projeto MVC (Para Iniciantes)

Bem-vindo ao seu projeto **MVC em PHP**! Se você está começando na programação agora ou nunca trabalhou com uma arquitetura organizada antes, este guia foi feito especialmente para você.

Aqui você aprenderá **o que é MVC**, como o projeto funciona por dentro e como criar páginas, formulários, bancos de dados e APIs passo a passo.

---

## 📑 Índice
1. [O que é MVC? (A analogia do Restaurante)](#1-o-que-é-mvc-a-analogia-do-restaurante)
2. [Como funciona o fluxo de uma requisição?](#2-como-funciona-o-fluxo-de-uma-requisição)
3. [Instalação: Rodando o projeto pela primeira vez](#3-instalação-rodando-o-projeto-pela-primeira-vez)
4. [O Mapa das Pastas (Onde você vai programar?)](#4-o-mapa-das-pastas-onde-você-vai-programar)
5. [Tutorial 1: Criando sua primeira página (Passo a Passo)](#5-tutorial-1-criando-sua-primeira-página-passo-a-passo)
6. [Tutorial 2: Estilizando com CSS, JavaScript e Imagens](#6-tutorial-2-estilizando-com-css-javascript-e-imagens)
7. [Tutorial 3: Formulários e Recebimento de Dados (POST e CSRF)](#7-tutorial-3-formulários-e-recebimento-de-dados-post-e-csrf)
8. [Tutorial 4: Banco de Dados sem Complicação (Migrations & Models)](#8-tutorial-4-banco-de-dados-sem-complicação-migrations--models)
9. [Tutorial 5: Criando uma API que responde JSON](#9-tutorial-5-criando-uma-api-que-responde-json)
10. [Comandos Mágicos do Terminal (`console`)](#10-comandos-mágicos-do-terminal-console)
11. [Dúvidas Frequentes e Erros Comuns](#11-dúvidas-frequentes-e-erros-comuns)

---

## 1. O que é MVC? (A analogia do Restaurante)

**MVC** significa **M**odel - **V**iew - **C**ontroller. É uma maneira de organizar o código para que cada arquivo tenha uma única responsabilidade.

Pense no seu site como um **Restaurante**:

```
 [CLIENTE / NAVEGADOR]
          │
          │ 1. Faz o pedido pelo cardápio (Acessa uma Rota no navegador)
          ▼
   [O GARÇOM: Controller]
          │
          │ 2. Pede os ingredientes na cozinha (Consulta dados)
          ▼
   [A COZINHA: Model & Banco de Dados]
          │
          │ 3. Devolve os dados prontos
          ▼
   [O GARÇOM: Controller]
          │
          │ 4. Monta o prato bonito (Junta os dados com o HTML)
          ▼
   [O PRATO SERVIDO: View] ──▶ O Cliente vê a tela no navegador!
```

- **Controller (O Garçom):** É o cérebro que atende o usuário. Ele recebe a requisição, decide o que precisa ser feito, busca os dados no Model e entrega uma View.
- **Model (A Cozinha / Banco de Dados):** É onde estão os dados. Se você precisa salvar um cliente, listar produtos ou verificar uma senha, é o Model quem conversa com o Banco de Dados.
- **View (O Prato Pronto):** É o que o usuário enxerga na tela: o HTML, o CSS e o visual. A View não deve fazer cálculos complexos nem mexer direto no banco de dados.

---

## 2. Como funciona o fluxo de uma requisição?

Quando alguém digita `http://localhost:8000/sobre` no navegador:

1. A requisição bate primeiro em `public/index.php`.
2. O arquivo de **Rotas** (`src/routes.php`) lê a URL e diz:  
   *"A rota `/sobre` deve chamar o método `sobre()` dentro do `HomeController`"*.
3. O **Controller** executa o método `sobre()`.
4. O Controller chama a **View** correspondente (`src/views/pages/sobre.php`).
5. A View é emoldurada dentro do **Layout Mestre** (`src/views/layouts/main.php`) e exibida na tela.

---

## 3. Instalação: Rodando o projeto pela primeira vez

### Pré-requisitos:
- PHP 8.0 ou superior instalado no seu computador.
- Composer instalado ([getcomposer.org](https://getcomposer.org)).

### Passo a Passo no Terminal:

1. **Abra o terminal na pasta do projeto** e instale as dependências:
   ```bash
   composer install
   ```

2. **Crie o arquivo de configurações:**
   Copie o arquivo `.env.example` criando um novo arquivo chamado `.env`:
   ```bash
   cp .env.example .env
   ```
   *(No Windows se preferir, pode copiar e colar manualmente renomeando para `.env`)*.

3. **Inicie o servidor local:**
   Execute o utilitário do projeto:
   ```bash
   php console serve
   ```
   Ou, se preferir o comando padrão do PHP:
   ```bash
   php -S localhost:8000 -t public
   ```

4. **Abra no navegador:**
   Acesse: **`http://localhost:8000`** e pronto! Sua aplicação estará rodando! 🎉

---

## 4. O Mapa das Pastas (Onde você vai programar?)

Você não precisa mexer em tudo! 90% do seu trabalho como desenvolvedor vai acontecer dentro de **`src/`** e **`public/assets/`**:

| Pasta / Arquivo | O que faz? | Devo mexer? |
| :--- | :--- | :--- |
| `src/routes.php` | Onde você cria os links/URLs do site | **Sim, sempre!** |
| `src/controllers/` | Os arquivos que cuidam da lógica de cada página | **Sim, sempre!** |
| `src/views/pages/` | As páginas HTML que o usuário vê | **Sim, sempre!** |
| `src/views/layouts/` | O esqueleto padrão do site (header, nav, footer) | **Sim, para alterar o design global** |
| `src/models/` | Classes que representam tabelas do banco de dados | **Sim, ao usar banco** |
| `public/assets/` | Seus arquivos de `css/`, `js/` e `images/` | **Sim, para o visual e scripts** |
| `database/migrations/`| Arquivos que criam e alteram tabelas no banco | **Sim, ao criar tabelas** |
| `.env` | Configuração de senhas e banco de dados | **Apenas na configuração inicial** |
| `core/` | O motor interno do framework | **Raramente (já está pronto)** |

---

## 5. Tutorial 1: Criando sua primeira página (Passo a Passo)

Vamos criar juntos uma página chamada **"Contato"** (`/contato`):

### Passo 1: Registrar a URL em `src/routes.php`
Abra o arquivo `src/routes.php` e adicione a seguinte linha:

```php
$router->get('/contato', 'ContatoController@index');
```
*Isso diz ao PHP: "Quando alguém entrar em `/contato`, execute a função `index` do `ContatoController`".*

---

### Passo 2: Criar o Controller
Você pode criar usando o terminal com o comando:
```bash
php console make:controller ContatoController
```
*Ou criar o arquivo manualmente em `src/controllers/ContatoController.php`:*

```php
<?php
namespace src\controllers;

use core\Controller;

class ContatoController extends Controller {

    public function index() {
        // Envia dados para a tela
        $dados = [
            'title' => 'Fale Conosco',
            'telefone' => '(11) 99999-9999',
            'email' => 'suporte@meusite.com'
        ];

        // Carrega a view 'pages/contato' usando o layout padrão
        $this->view('pages/contato', $dados);
    }
}
```

---

### Passo 3: Criar o arquivo de View (HTML)
Crie o arquivo `src/views/pages/contato.php`:

```php
<div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 2rem;">
    <h1><?= e($title) ?></h1>
    <p>Entre em contato com nossa equipe:</p>

    <ul>
        <li>Telefone: <?= e($telefone) ?></li>
        <li>E-mail: <?= e($email) ?></li>
    </ul>
</div>
```

> **Dica de Ouro:** Repare na função `<?= e($variavel) ?>`. Ela serve para proteger seu site contra ataques hacker (XSS), garantindo que textos especiais não executem scripts perigosos no navegador. Use sempre que for exibir variáveis!

Pronto! Acesse **`http://localhost:8000/contato`** no navegador e veja sua nova página funcionando!

---

## 6. Tutorial 2: Estilizando com CSS, JavaScript e Imagens

Todos os arquivos visuais e estáticos devem ficar dentro da pasta **`public/assets/`**:

```text
public/assets/
├── css/
│   └── style.css      <-- Seus estilos CSS
├── js/
│   └── app.js         <-- Seus scripts JavaScript
└── images/            <-- Suas fotos, logos e ícones
```

### Como chamar esses arquivos no HTML?
Nunca escreva caminhos manuais como `../../assets/img.png`, pois eles quebram dependendo da página em que você estiver.  
Use a função mágica **`asset()`**:

```html
<!-- Carregando uma Imagem -->
<img src="<?= asset('images/logo.png') ?>" alt="Minha Logo">

<!-- Carregando um CSS adicional -->
<link rel="stylesheet" href="<?= asset('css/custom.css') ?>">

<!-- Carregando um Script adicional -->
<script src="<?= asset('js/meu-script.js') ?>"></script>
```

---

## 7. Tutorial 3: Formulários e Recebimento de Dados (POST e CSRF)

Quando enviamos dados de um formulário (como login, cadastro ou mensagens), usamos o método **POST**.

### 1. Criando o Formulário na View
Adicione um formulário na sua página:

```html
<form method="POST" action="<?= base_url('/contato/enviar') ?>">
    <!-- REGRA OBRIGATÓRIA DE SEGURANÇA: Token CSRF -->
    <?= csrf_field() ?>

    <label>Seu Nome:</label>
    <input type="text" name="nome" required>

    <label>Sua Mensagem:</label>
    <textarea name="mensagem" required></textarea>

    <button type="submit">Enviar Mensagem</button>
</form>
```

> ⚠️ **O que é o `<?= csrf_field() ?>`?**  
> É um código de segurança invisível gerado pelo sistema. Ele impede que sites maliciosos enviem formulários fingindo ser você. Se você esquecer essa linha em um formulário POST, o sistema bloqueará o envio exibindo o erro `419 - Página Expirada`.

---

### 2. Criando a rota de recebimento em `src/routes.php`
```php
$router->post('/contato/enviar', 'ContatoController@enviar');
```

---

### 3. Recebendo e tratando os dados no Controller
No seu `ContatoController.php`:

```php
public function enviar() {
    // 1. Pega os dados enviados pelo formulário
    $nome = $this->request->input('nome');
    $mensagem = $this->request->input('mensagem');

    // 2. Validação simples
    if (empty($nome) || empty($mensagem)) {
        // Envia mensagem de erro temporária (Flash)
        flash('error', 'Por favor, preencha todos os campos.');
        $this->redirect('/contato');
        return;
    }

    // 3. Salva no banco ou envia e-mail...
    // (Lógica da sua aplicação aqui)

    // 4. Cria mensagem de sucesso e redireciona de volta
    flash('success', "Obrigado, {$nome}! Sua mensagem foi enviada.");
    $this->redirect('/contato');
}
```

---

## 8. Tutorial 4: Banco de Dados sem Complicação (Migrations & Models)

### Passo 1: Configurar a conexão no `.env`
Abra o arquivo `.env` na raiz do projeto e coloque os dados do seu MySQL:

```env
DB_DRIVER=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nomedoseubanco
DB_USER=root
DB_PASS=suasenha
```

---

### Passo 2: Criar uma Tabela com Migrations
Em vez de entrar no phpMyAdmin e criar tabelas manualmente, usamos **Migrations**. As migrations garantem que qualquer pessoa que baixar seu código consiga recriar o banco com 1 comando.

Gere uma migration no terminal:
```bash
php console make:migration create_produtos_table
```
Isso vai criar um arquivo dentro de `database/migrations/`. Abra o arquivo gerado e defina os campos:

```php
<?php
use core\Migration;

return new class extends Migration {
    public function up(\PDO $pdo): void {
        $sql = "CREATE TABLE IF NOT EXISTS produtos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            preco DECIMAL(10, 2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $pdo->exec($sql);
    }

    public function down(\PDO $pdo): void {
        $pdo->exec("DROP TABLE IF EXISTS produtos;");
    }
};
```

Agora execute no terminal para criar a tabela no MySQL:
```bash
php console migrate
```

---

### Passo 3: Criar o Model
O Model é a classe PHP que representa a tabela `produtos`. Crie pelo terminal:
```bash
php console make:model Produto
```
Ele cria o arquivo `src/models/Produto.php`:
```php
<?php
namespace src\models;

use core\Model;

class Produto extends Model {
    protected static ?string $table = 'produtos';
    protected static string $primaryKey = 'id';
}
```

---

### Passo 4: Como consultar e salvar dados no Controller

#### Para Cadastrar um novo registro:
```php
use src\models\Produto;

Produto::create([
    'nome' => 'Pizza Calabresa',
    'preco' => 45.90
]);
```

#### Para Listar todos os registros:
```php
$todosProdutos = Produto::all();
```

#### Para Buscar pelo ID:
```php
$produto = Produto::find(1); // Busca o produto com id = 1
```

#### Para Filtrar registros:
```php
$baratos = Produto::where('preco', '<', 50)->get();
```

---

## 9. Tutorial 5: Criando uma API que responde JSON

Se você estiver desenvolvendo um app mobile ou usando React, Vue ou frontend moderno, vai precisar de respostas em formato **JSON**.

### Passo 1: Criar a rota da API em `src/routes.php`
```php
$router->group(['prefix' => '/api'], function($router) {
    $router->get('/produtos', 'ProdutoController@apiListar');
});
```

---

### Passo 2: Responder JSON no Controller
```php
namespace src\controllers;

use core\Controller;
use src\models\Produto;

class ProdutoController extends Controller {

    public function apiListar() {
        $produtos = Produto::all();

        // O método json() já define o cabeçalho 'application/json' e o status code!
        $this->json([
            'status' => 'success',
            'total' => count($produtos),
            'data' => $produtos
        ], 200);
    }
}
```

---

## 10. Comandos Mágicos do Terminal (`console`)

Para agilizar o desenvolvimento, o projeto conta com o utilitário **`console`**:

```bash
# Ver a lista de todos os comandos
php console list

# Criar um novo Controller para páginas normais
php console make:controller ClienteController

# Criar um Controller já preparado para API JSON
php console make:controller ClienteController --api

# Criar um Model
php console make:model Cliente

# Criar um Model e a Migration da tabela ao mesmo tempo
php console make:model Cliente -m

# Criar uma migração avulsa
php console make:migration create_pedidos_table

# Rodar todas as migrações pendentes no banco
php console migrate

# Desfazer o último lote de migrações
php console migrate:rollback

# Ligar o servidor local
php console serve
# Ou em uma porta diferente:
php console serve --port=8080
```

---

## 11. Dúvidas Frequentes e Erros Comuns

### 🔴 Erro 404 - Página Não Encontrada
- **O que significa?** Você tentou acessar uma URL que não existe em `src/routes.php`.
- **Como resolver:** Abra `src/routes.php` e confira se a rota digitada está escrita exatamente igual (atenção a letras maiúsculas/minúsculas e barras `/`).

### 🔴 Erro 419 - Página Expirada
- **O que significa?** Um formulário do tipo POST foi enviado sem o token de proteção CSRF.
- **Como resolver:** Adicione `<?= csrf_field() ?>` dentro do seu `<form method="POST">`.

### 🔴 Erro: "View não encontrada"
- **O que significa?** O Controller tentou chamar uma tela que não existe no disco.
- **Como resolver:** Se você colocou `$this->view('pages/minha_tela')`, certifique-se de que o arquivo `src/views/pages/minha_tela.php` realmente existe.

### 🔴 Erro ao conectar com o banco de dados
- **O que significa?** O MySQL não está ligado ou os dados no `.env` estão incorretos.
- **Como resolver:** Abra o `.env`, confira se o usuário (`root`) e a senha estão corretos, e verifique se o serviço do MySQL/MariaDB está rodando no seu computador.

---

🎉 **Parabéns!** Agora você tem em mãos uma base sólida, profissional e fácil de entender para construir qualquer projeto em PHP. Bons estudos e bom código!