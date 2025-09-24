# Sistema de Gestão de RH

Um sistema de gestão de recursos humanos desenvolvido com Laravel 11, utilizando Laravel Fortify para autenticação e PestPHP para testes.

## 📋 Índice

-   [Tecnologias Utilizadas](#tecnologias-utilizadas)
-   [Funcionalidades](#funcionalidades)
-   [Estrutura do Projeto](#estrutura-do-projeto)
-   [Sistema de Autenticação](#sistema-de-autenticação)
-   [Instalação](#instalação)
-   [Testes](#testes)
-   [Rotas Principais](#rotas-principais)

## 🚀 Tecnologias Utilizadas

-   **PHP**: ^8.2
-   **Laravel Framework**: ^11.9
-   **Laravel Fortify**: ^1.21 (Autenticação)
-   **PestPHP**: ^3.7 (Testes)
-   **Bootstrap**: Frontend
-   **DataTables**: Manipulação de tabelas
-   **FontAwesome**: Ícones

## ✨ Funcionalidades

### Sistema de Autenticação

-   Login de usuários
-   Recuperação de senha
-   Confirmação de conta via email
-   Middleware de autenticação
-   Sistema de roles e permissões

### Gestão de Usuários

-   Cadastro de colaboradores
-   Perfis de usuário
-   Departamentos
-   Administração do sistema

## 📁 Estrutura do Projeto

```
rh_mangnt/
├── app/
│   ├── Http/Controllers/        # Controladores da aplicação
│   ├── Models/                  # Modelos Eloquent
│   │   ├── User.php            # Modelo de usuário
│   │   ├── UserDetail.php      # Detalhes do usuário
│   │   └── Department.php      # Departamentos
│   ├── Mail/                   # Classes de email
│   └── Providers/              # Service Providers
├── database/
│   ├── migrations/             # Migrações do banco
│   └── seeders/               # Seeders
├── tests/
│   └── Feature/
│       └── AuthTest.php       # Testes de autenticação
└── resources/views/           # Views Blade
```

## 🔐 Sistema de Autenticação

O sistema utiliza Laravel Fortify para gerenciar autenticação. Baseado nos testes implementados, as seguintes funcionalidades foram validadas:

### Fluxo de Login

#### 1. Redirecionamento para Login

-   **Comportamento**: Usuários não autenticados são redirecionados para `/login`
-   **Status HTTP**: 302 (Redirect)
-   **Teste**: Verifica se usuários não logados são redirecionados corretamente

```php
// Teste implementado
it('display the login page when not logget in', function () {
    $result = $this->get('/')->assertRedirect("/login");
    expect($result->status())->toBe(302);
    expect($this->get('/login')->status())->toBe(200);
    expect($this->get('/login')->content())->toContain("Esqueceu a sua senha?");
});
```

#### 2. Página de Login

-   **Rota**: `/login`
-   **Status HTTP**: 200 (OK)
-   **Elementos**: Contém link "Esqueceu a sua senha?"
-   **Funcionalidade**: Formulário de autenticação

#### 3. Recuperação de Senha

-   **Rota**: `/forgot-password`
-   **Status HTTP**: 200 (OK)
-   **Elementos**: Contém link "Já sei a minha senha?"
-   **Funcionalidade**: Formulário para recuperação de senha

```php
// Teste implementado
it("Forgot password", function () {
    $result = $this->get('/forgot-password');
    expect($result->status())->toBe(200);
    expect($result->content())->toContain("Já sei a minha senha?");
});
```

#### 4. Autenticação de Administrador

-   **Credenciais de Teste**:

    -   Email: `admin@rhmangnt.com`
    -   Senha: `Aa123456`
    -   Role: `admin`
    -   Permissões: `["admin"]`

-   **Fluxo de Login**:
    1. POST para `/login` com credenciais
    2. Redirecionamento (302) para `/home`
    3. Acesso autorizado ao sistema

```php
// Teste implementado (refatorado com função auxiliar)
it('testing if an admin user can login with success', function () {
    addAdminUser(); // Utiliza função auxiliar para criar usuário

    // Teste de login
    $result = $this->post('/login', [
        'email' => 'admin@rhmangnt.com',
        'password' => 'Aa123456'
    ]);

    expect($result->status())->toBe(302);
    expect($result->assertRedirect('/home'));
});

// Função auxiliar para criação de usuário administrador
function addAdminUser() {
    User::insert([
        'department_id' => 1,
        'name' => 'Administrador',
        'email' => 'admin@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'admin',
        'permissions' => '["admin"]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
```

### Estrutura do Usuário

O modelo `User` possui os seguintes campos principais:

-   `department_id`: ID do departamento
-   `name`: Nome completo
-   `email`: Email (único)
-   `email_verified_at`: Data de verificação do email
-   `password`: Senha criptografada
-   `role`: Função do usuário (admin, user, etc.)
-   `permissions`: Permissões em formato JSON
-   `remember_token`: Token para "lembrar de mim"
-   `confirmation_token`: Token para confirmação de conta

## 🛠️ Instalação

1. **Clone o repositório**:

```bash
git clone <repository-url>
cd rh_mangnt
```

2. **Instale as dependências**:

```bash
composer install
npm install
```

3. **Configure o ambiente**:

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados** no arquivo `.env`

5. **Execute as migrações**:

```bash
php artisan migrate
```

6. **Execute os seeders** (se necessário):

```bash
php artisan db:seed
```

7. **Compile os assets**:

```bash
npm run dev
```

8. **Inicie o servidor**:

```bash
php artisan serve
```

## 🧪 Testes

O projeto utiliza PestPHP para testes. Os testes estão localizados em `tests/Feature/AuthTest.php`.

### Executar Testes

```bash
# Executar todos os testes
./vendor/bin/pest

# Executar testes específicos de autenticação
./vendor/bin/pest tests/Feature/AuthTest.php

# Executar com coverage (se configurado)
./vendor/bin/pest --coverage
```

### Testes Implementados

#### Testes de Autenticação (`AuthTest.php`)

1. **Teste de Redirecionamento**: Verifica se usuários não autenticados são redirecionados para login
2. **Teste de Página de Recuperação**: Valida a funcionalidade de esqueci minha senha
3. **Teste de Login de Admin**: Confirma que usuários administradores podem fazer login com sucesso
4. **Teste de Login de Usuário RH**: Valida login de usuários RH e acesso a rotas específicas
5. **Teste de Autorização Negativa**: Verifica que colaboradores **NÃO** têm acesso a rotas de RH

#### Testes de Criação de Usuários (`CreateUserTest.php`)

1. **Teste de Criação de Usuário RH**: Valida que administradores podem criar novos usuários RH através da interface web
2. **Teste de Criação de Colaborador por RH**: Valida que usuários RH podem criar colaboradores e verifica autenticação de usuário logado

#### Funções Auxiliares nos Testes

Para melhorar a organização e reutilização de código nos testes, foram implementadas funções auxiliares:

**`addAdminUser()`**: Função auxiliar para criação de usuário administrador

-   **Propósito**: Centralizar a criação de usuários admin para testes
-   **Benefícios**:
    -   Reduz duplicação de código
    -   Facilita manutenção dos testes
    -   Padroniza dados de teste
    -   Melhora legibilidade dos testes

```php
function addAdminUser() {
    User::insert([
        'department_id' => 1,
        'name' => 'Administrador',
        'email' => 'admin@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'admin',
        'permissions' => '["admin"]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
```

**Vantagens da Refatoração**:

-   ✅ Código mais limpo e organizado
-   ✅ Reutilização em múltiplos testes
-   ✅ Facilita futuras modificações nos dados de teste
-   ✅ Melhor manutenibilidade do código de teste

#### Sistema de Sessões nos Testes

**`addRHUser()`**: Função auxiliar para criação de usuário RH

-   **Propósito**: Criar usuários com role 'rh' para testes de autorização
-   **Department ID**: 2 (diferente do admin)
-   **Email**: admin1@rhmangnt.com

```php
function addRHUser() {
    User::insert([
        'department_id' => 2,
        'name' => 'Administrador',
        'email' => 'admin1@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'rh',
        'permissions' => '["admin"]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
```

**`addCollaborator()`**: Função auxiliar para criação de colaborador

-   **Propósito**: Criar usuários com role 'collaborator' para testes de autorização negativa
-   **Department ID**: 1 (mesmo que admin, mas role diferente)
-   **Email**: worker@rhmangnt.com
-   **Uso**: Validar que colaboradores não têm acesso a áreas administrativas

```php
function addCollaborator() {
    User::insert([
        'department_id' => 1,
        'name' => 'collaborator',
        'email' => 'worker@rhmangnt.com',
        'email_verified_at' => now(),
        'password' => bcrypt('Aa123456'),
        'role' => 'collaborator',
        'permissions' => '["colaborator"]',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
```

**`addDepartment()`**: Função auxiliar para criação de departamentos

-   **Propósito**: Criar departamentos para testes que envolvem relacionamentos
-   **Parâmetro**: `$name` - Nome do departamento a ser criado
-   **Uso**: Essencial para testes de criação de usuários que precisam de department_id válido

```php
function addDepartment($name) {
    Department::insert([
        "name" => $name,
        "created_at" => Carbon::now(),
        "updated_at" => Carbon::now(),
    ]);
}
```

### Comportamento de Autenticação e Sessões

#### Fluxo de Login Padrão

**Importante**: Independente do role do usuário (admin, rh, etc.), **todos os logins são redirecionados para `/home`** após a autenticação bem-sucedida. A partir do `/home`, a aplicação faz o roteamento interno baseado no role do usuário.

**Comportamento do Sistema:**

1. **Login bem-sucedido** → Redirect HTTP 302 para `/home`
2. **Página Home** → Avalia o role do usuário
3. **Roteamento interno** → Direciona para a área específica do role
4. **Área específica** → Status 200 (acesso permitido)

#### Como funciona a persistência de sessão nos testes:

```php
it('testing if an rh user can login in Admin route', function () {
    addRHUser(); // Cria usuário RH no banco de teste

    // 1. Faz login - autentica o usuário na sessão
    $result = $this->post('/login', [
        'email' => 'admin1@rhmangnt.com',
        'password' => 'Aa123456'
    ]);

    // 2. Verifica redirect HTTP após login (302)
    expect($result->status())->toBe(302);
    expect($result->assertRedirect('/home'));

    // 3. A sessão está MANTIDA - pode acessar rotas protegidas
    expect($this->get('rh-users/management/home')->status())->toBe(200);
});
```

#### Características importantes da sessão em testes:

| Aspecto              | Comportamento                | Explicação                                     |
| -------------------- | ---------------------------- | ---------------------------------------------- |
| **Persistência**     | ✅ Mantida entre requisições | O framework preserva o estado de autenticação  |
| **Escopo**           | 🎯 Por teste individual      | Cada `it()` tem sua própria sessão isolada     |
| **Autenticação**     | 🔐 Válida após POST `/login` | Login bem-sucedido autentica para todo o teste |
| **Rotas protegidas** | 🛡️ Acessíveis após login     | Middleware de auth reconhece a sessão ativa    |

#### Diferença entre Redirects:

```php
// ❌ REDIRECT HTTP - Gera status 302
$this->post('/login', $credentials)
    ->assertStatus(302)           // Redirect HTTP
    ->assertRedirect('/home');    // Destino do redirect

// ✅ ACESSO DIRETO - Gera status 200
$this->get('rh-users/management/home')
    ->assertStatus(200);          // Acesso bem-sucedido (sessão ativa)
```

#### Testes de Autorização Negativa (usando `not()`)

Para garantir que o sistema de autorização funciona corretamente, implementamos **testes negativos** que verificam quando usuários **NÃO** devem ter acesso a determinadas rotas.

**Exemplo: Colaborador tentando acessar área de RH**

```php
it('Testing if a collaborator can go to the home route.', function () {
    addCollaborator(); // Cria usuário collaborator

    // 1. Login bem-sucedido (colaborador pode se autenticar)
    $result = $this->post('/login', [
        'email' => 'worker@rhmangnt.com',
        'password' => 'Aa123456'
    ]);

    expect($result->status())->toBe(302);
    expect($result->assertRedirect('home'));

    // 2. TESTE NEGATIVO: Colaborador NÃO deve ter acesso à área de RH
    expect($this->get('rh-users/management/home')->status())
        ->not()->toBe(200); // Usando not() para assertiva negativa
});
```

#### Vantagens dos Testes Negativos:

| Benefício          | Explicação                                              |
| ------------------ | ------------------------------------------------------- |
| **Segurança**      | ✅ Garante que usuários não têm acesso indevido         |
| **Autorização**    | 🔐 Valida que roles e permissões funcionam corretamente |
| **Cobertura**      | 📊 Testa tanto cenários positivos quanto negativos      |
| **Confiabilidade** | 🛡️ Confirma que o sistema bloqueia acessos inadequados  |

#### Sintaxe do `not()` no PestPHP:

```php
// ✅ Teste positivo
expect($status)->toBe(200);

// ❌ Teste negativo usando not()
expect($status)->not()->toBe(200);

// Equivale a verificar que o status NÃO é 200
// Pode ser 403 (Forbidden), 404 (Not Found), etc.
```

**Importante**: O teste negativo com `not()->toBe(200)` verifica que o acesso foi **negado**, mas não especifica o código exato (403, 404, 401, etc.). Isso é útil quando queremos apenas confirmar que o acesso foi bloqueado, independente do tipo específico de erro retornado.

#### Tipos de Usuário nos Testes:

**Admin User:**

-   **Email:** `admin@rhmangnt.com`
-   **Role:** `admin`
-   **Department ID:** `1`
-   **Acesso:** Todas as áreas administrativas

**RH User:**

-   **Email:** `admin1@rhmangnt.com`
-   **Role:** `rh`
-   **Department ID:** `2`
-   **Acesso:** `rh-users/management/home` e áreas de RH

**Collaborator User:**

-   **Email:** `worker@rhmangnt.com`
-   **Role:** `collaborator`
-   **Department ID:** `1`
-   **Acesso:** Limitado - **NÃO** tem acesso a áreas de RH ou admin
-   **Uso nos testes:** Validação de autorização negativa

### Testes de Banco de Dados e Verificação de Registros

#### Teste de Criação de Usuário RH

O teste `CreateUserTest.php` implementa um cenário completo de criação de usuário através da interface web, validando tanto o processo quanto a persistência no banco de dados.

**Fluxo do teste:**

```php
it('tests if an admin can insert a new RH user', function () {
    // 1. Preparação: Criar usuário admin
    addAdminUser();

    // 2. Preparação: Criar departamentos necessários
    addDepartment('Administração');      // ID: 1
    addDepartment('Recursos Humanos');   // ID: 2

    // 3. Autenticação: Login como admin
    $result = $this->post('/login', [
        'email' => 'admin@rhmangnt.com',
        'password' => 'Aa123456'
    ]);

    expect($result->status())->toBe(302);
    expect($result->assertRedirect('/home'));

    // 4. Ação: Criar novo usuário RH via POST
    $value = $this->post('/rh-users/create-colaborator', [
        'name' => 'RH USER 1',
        'email' => 'rhuser55@gmail.com',
        'select_department' => 2,           // Departamento RH
        'address' => 'Rua 1',
        'zip_code' => '1234-123',
        'city' => '123-City',
        'phone' => '123123123',
        'salary' => '1000.00',
        'admission_date' => '2021-01-01',
        'role' => 'rh',
        'permissions' => '["rh"]'
    ]);

    // 5. Verificação: Confirmar registro no banco de dados
    $this->assertDatabaseHas('users', [
        'name' => 'RH USER 1',
        'email' => 'rhuser55@gmail.com',
        'role' => 'rh'
    ]);
});
```

#### Como funciona `assertDatabaseHas()`

A função `assertDatabaseHas()` é uma ferramenta poderosa do Laravel para verificar se registros existem no banco de dados durante os testes.

**Sintaxe:**

```php
$this->assertDatabaseHas('nome_da_tabela', [
    'campo1' => 'valor1',
    'campo2' => 'valor2',
    // ... mais campos conforme necessário
]);
```

**Características importantes:**

| Aspecto             | Comportamento                            | Explicação                                       |
| ------------------- | ---------------------------------------- | ------------------------------------------------ |
| **Verificação**     | 🔍 Busca na tabela especificada          | Executa query real no banco de teste             |
| **Correspondência** | ✅ Todos os campos devem coincidir       | Funciona como WHERE com AND                      |
| **Flexibilidade**   | 📊 Verifica apenas campos especificados  | Não precisa verificar todos os campos da tabela  |
| **Falha**           | ❌ Teste falha se não encontrar registro | Garante que a operação realmente persistiu dados |

#### Vantagens dos Testes de Banco de Dados:

```php
// ✅ VANTAGEM: Verifica persistência real
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);

// ✅ VANTAGEM: Valida relacionamentos
$this->assertDatabaseHas('users', [
    'email' => 'rhuser55@gmail.com',
    'department_id' => 2  // Verifica se FK foi salva corretamente
]);

// ✅ VANTAGEM: Confirma processamento de dados
$this->assertDatabaseHas('user_details', [
    'salary' => '1000.00',      // Confirma formatação de decimal
    'admission_date' => '2021-01-01'  // Confirma conversão de data
]);
```

#### Preparação de Dados para Testes Complexos:

**1. Dependências de relacionamento:**

```php
// Ordem importa: criar departamentos antes de usuários
addDepartment('Administração');      // ID: 1
addDepartment('Recursos Humanos');   // ID: 2

// Agora pode referenciar department_id = 2
'select_department' => 2
```

**2. Dados de teste realistas:**

-   **Endereço completo**: address, zip_code, city
-   **Informações profissionais**: salary, admission_date, phone
-   **Dados de autenticação**: role, permissions

#### Teste de Usuário RH Criando Colaborador

O segundo teste implementa um cenário onde um **usuário RH** (não admin) cria um colaborador, demonstrando diferentes níveis de permissão e métodos de verificação de banco.

**Fluxo do teste:**

```php
it('test if a RH user can to insert an user', function () {
    // 1. Preparação: Criar departamentos (3 para teste)
    addDepartment('Administração');     // ID: 1
    addDepartment('Recursos Humanos');  // ID: 2
    addDepartment('teste3');            // ID: 3

    // 2. Preparação: Criar usuário RH
    addRHUser(); // (department_id: 2, role: 'rh')

    // 3. Autenticação: Login como RH
    $result = $this->post('/login', [
        'email' => 'admin1@rhmangnt.com',
        'password' => 'Aa123456'
    ]);

    // 4. Verificação: Confirmar role do usuário logado
    expect(auth()->user()->role)->toBe('rh');

    // 5. Ação: RH cria colaborador (rota diferente do admin)
    $this->post('/rh-users/management/create-colaborator', [
        'name' => 'colaborator USER 1',
        'email' => 'colaboratoruser5@gmail.com',
        'select_department' => 3,        // Departamento 'teste3'
        'address' => 'Rua 1',
        'zip_code' => '1234-123',
        'city' => '123-City',
        'phone' => '123123123',
        'salary' => '1000.00',
        'admission_date' => '2021-01-01',
        'role' => 'colaborator',
        'permissions' => '["colaborator"]'
    ]);

    // 6. Verificação alternativa: Usando Eloquent where()
    $values_where = [
        ['email', '=', 'colaboratoruser5@gmail.com'],
        ['name', '=', 'colaborator USER 1'],
        ['role', '=', 'colaborator']
    ];

    expect(User::where($values_where)->exists())->toBeTrue();
});
```

#### Métodos de Verificação de Banco de Dados

##### **1. Laravel Testing: `assertDatabaseHas()`**

```php
// ✅ Método tradicional do Laravel
$this->assertDatabaseHas('users', [
    'name' => 'colaborator USER 1',
    'email' => 'colaboratoruser5@gmail.com',
    'role' => 'colaborator',
]);
```

##### **2. PestPHP + Eloquent: `where()->exists()`**

```php
// ✅ Método alternativo com PestPHP
$values_where = [
    ['email', '=', 'colaboratoruser5@gmail.com'],
    ['name', '=', 'colaborator USER 1'],
    ['role', '=', 'colaborator']
];

expect(User::where($values_where)->exists())->toBeTrue();
```

#### Diferenças entre os Métodos:

| Aspecto           | `assertDatabaseHas()`        | `where()->exists()`             |
| ----------------- | ---------------------------- | ------------------------------- |
| **Framework**     | 🔧 Laravel Testing           | 🧪 PestPHP + Eloquent           |
| **Sintaxe**       | 📝 Array associativo simples | 📊 Array de condições múltiplas |
| **Flexibilidade** | ⚡ Direto e simples          | 🎯 Mais controle sobre queries  |
| **Performance**   | 🚀 Query otimizada           | 🔍 Query Eloquent padrão        |
| **Uso**           | 💡 Para verificações simples | 🛠️ Para condições complexas     |

#### Verificação de Usuário Autenticado

**Nova funcionalidade demonstrada:**

```php
// Verificar role do usuário logado na sessão
expect(auth()->user()->role)->toBe('rh');
```

**Benefícios:**

-   ✅ **Confirmação de contexto**: Garante que o usuário correto está logado
-   ✅ **Validação de role**: Confirma que o teste está rodando no contexto adequado
-   ✅ **Debug auxiliar**: Ajuda a identificar problemas de autenticação nos testes

#### Cenários Validados no CreateUserTest:

**Teste 1 - Admin criando usuário RH:**

-   ✅ **Autorização**: Apenas admins podem criar usuários RH
-   ✅ **Autenticação**: Login necessário antes da operação
-   ✅ **Formulário web**: POST para rota `/rh-users/create-colaborator`
-   ✅ **Persistência**: Dados salvos com `assertDatabaseHas()`
-   ✅ **Relacionamentos**: Department_id é associado corretamente

**Teste 2 - RH criando colaborador:**

-   ✅ **Hierarquia de permissões**: RH pode criar colaboradores (não apenas admin)
-   ✅ **Rotas diferentes**: `/rh-users/management/create-colaborator` (rota específica para RH)
-   ✅ **Verificação de contexto**: `expect(auth()->user()->role)->toBe('rh')`
-   ✅ **Múltiplos departamentos**: Teste com 3 departamentos para flexibilidade
-   ✅ **Método alternativo**: Verificação com `User::where()->exists()`
-   ✅ **Dados estruturados**: Informações pessoais e profissionais completas

### Cenários de Teste Cobertos

**Testes de Autenticação:**

-   ✅ Redirecionamento de usuários não autenticados
-   ✅ Exibição correta da página de login
-   ✅ Funcionalidade de recuperação de senha
-   ✅ Login bem-sucedido de usuário administrador
-   ✅ Login bem-sucedido de usuário RH
-   ✅ Login bem-sucedido de colaborador
-   ✅ Redirecionamento pós-login para home (todos os roles)
-   ✅ Acesso a rotas protegidas após autenticação
-   ✅ **Autorização negativa**: Colaborador NÃO acessa área de RH
-   ✅ Persistência de sessão entre requisições no teste
-   ✅ Validação de diferentes tipos de usuário (admin/rh/collaborator)
-   ✅ Testes com assertivas negativas usando `not()`

**Testes de Criação de Usuários:**

-   ✅ **Criação de usuário RH por Admin**: Admin pode criar novos usuários RH
-   ✅ **Criação de colaborador por RH**: RH pode criar colaboradores
-   ✅ **Verificação de contexto**: `auth()->user()->role` confirma usuário logado
-   ✅ **Múltiplos métodos de verificação**: `assertDatabaseHas()` e `where()->exists()`
-   ✅ **Rotas hierárquicas**: Diferentes rotas para admin e RH
-   ✅ **Relacionamentos**: Department_id é associado corretamente
-   ✅ **Dados complexos**: Informações pessoais e profissionais completas
-   ✅ **Preparação de dependências**: Criação de múltiplos departamentos
-   ✅ **Formulário web completo**: Testes end-to-end das funcionalidades

## 🔗 Rotas Principais

### Rotas Públicas (Guest)

-   `GET /login` - Página de login
-   `GET /forgot-password` - Recuperação de senha
-   `GET /confirm-account/{token}` - Confirmação de conta
-   `POST /confirm-account` - Processamento da confirmação

### Rotas Autenticadas

-   `GET /home` - Dashboard principal
-   `GET /` - Redirecionamento para login (se não autenticado)
-   `GET /rh-users/management/home` - Área de gestão de RH
-   `POST /rh-users/create-colaborator` - Criação de usuários RH (rota admin)
-   `POST /rh-users/management/create-colaborator` - Criação de colaboradores (rota RH)

### Middleware

-   `guest` - Para usuários não autenticados
-   `auth` - Para usuários autenticados

## 📧 Sistema de Email

O sistema inclui funcionalidades de email para:

-   Confirmação de conta (`ConfirmAccountEmail.php`)
-   Recuperação de senha
-   Notificações do sistema

## 🔒 Segurança

-   Senhas criptografadas com bcrypt
-   Tokens de confirmação para novos usuários
-   Sistema de roles e permissões
-   Middleware de autenticação
-   Validação de email antes do acesso

## 📝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

---

**Sistema de Gestão de RH** - Desenvolvido com Laravel 11 e PestPHP
