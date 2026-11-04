# Diferenças encontradas — Laravel 7 vs Laravel 13

Comparação entre a versão 7 e a versão 13 do Laravel, com base na versão instalada neste projeto (**Laravel Framework 13.40.0**, PHP 8.5.12).

---

## 1. Versão do PHP requerida

| Funcionalidade | Laravel 7 | Laravel 13 |
|---|---|---|
| Versão mínima do PHP | 7.2.5 | 8.3 |
| Versão usada neste projeto | — | 8.5.12 |

O Laravel 13 requer PHP 8.3+, o que permite usar recursos modernos da linguagem (match expressions, named arguments, constructor property promotion, attributes, readonly classes, etc.).

---

## 2. Estrutura de diretórios e bootstrap

| Item | Laravel 7 | Laravel 13 |
|---|---|---|
| Kernel HTTP | `app/Http/Kernel.php` | Removido; middleware configurado em `bootstrap/app.php` |
| Kernel Console | `app/Console/Kernel.php` | Mantido, mas configurável via `bootstrap/app.php` |
| Ficheiro de bootstrap principal | `public/index.php` (carrega `vendor/autoload.php` e `bootstrap/app.php`) | Mesmo princípio, mas `bootstrap/app.php` é o ponto central de configuração |
| Diretório `app/Http/Middleware` | Mantido | Mantido |

No Laravel 13, a configuração global (middleware, exceptions, etc.) foi migrada dos kernels para `bootstrap/app.php`, tornando a estrutura mais simples.

---

## 3. Modelos Eloquent

Esta é uma das áreas com mais mudanças visíveis no projeto.

### 3.1 Atributos `fillable` e `guarded`

**Laravel 7** (propriedades de classe):

```php
class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'duration', 'status', 'value'];
    protected $guarded = [];
}
```

**Laravel 13** (atributos PHP 8):

```php
use Illuminate\Database\Eloquent\Attributes\Fillable;

class Course extends Model
{
    use HasFactory;

    #[Fillable(['name', 'description', 'duration', 'status', 'value'])]
    protected array $fillable = ['name', 'description', 'duration', 'status', 'value'];
}
```

### 3.2 Casts

**Laravel 7** (propriedade `$casts`):

```php
protected $casts = [
    'status' => 'boolean',
    'days_of_week' => 'array',
];
```

**Laravel 13** (método `casts()`):

```php
protected function casts(): array
{
    return [
        'status' => 'boolean',
        'days_of_week' => 'array',
    ];
}
```

No Laravel 13, o uso de `$casts` como propriedade ainda funciona, mas a abordagem recomendada é o método `casts(): array`, que permite maior flexibilidade (lógica condicional, etc.).

### 3.3 Soft Deletes

**Laravel 7**:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
}
```

**Laravel 13**:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;
}
```

O comportamento é semelhante, mas no Laravel 13 o `deleted_at` é gerido automaticamente com o trait `SoftDeletes` sem necessidade de configurar `$dates`.

---

## 4. Controladores

| Item | Laravel 7 | Laravel 13 |
|---|---|---|
| Classe base | `App\Http\Controllers\Controller` | `App\Http\Controllers\Controller` (abstrata) |
| Injeção de dependência | Suportada | Suportada (melhorada com PHP 8 attributes) |
| Route model binding | Suportado | Suportado (melhor desempenho) |
| Métodos de recurso | `index`, `create`, `store`, `show`, `edit`, `update`, `destroy` | Mesmos métodos |

Os controladores criados neste projeto seguem o padrão RESTful com os 7 métodos de recurso, injeção de `Illuminate\Http\Request` e validação via `$request->validate()`.

---

## 5. Blade e Views

| Item | Laravel 7 | Laravel 13 |
|---|---|---|
| Componentes Blade | Introduzidos (class-based + anonymous) | Melhorados, mais performantes |
| Diretivas `@component`, `@slot` | Suportadas | Suportadas |
| Laravel UI / Jetstream | Laravel UI disponível | Jetstream, Fortify, Breeze (ecosistema mais maduro) |
| Vite | Não (usava Laravel Mix) | Suportado nativamente (`vite.config.js`) |

Este projeto usa **Vite** para assets (ver `vite.config.js`) e **Blade** para vistas, conforme documentado no `ESTRUTURA_DO_PROJETO.md`.

---

## 6. Base de dados e Query Builder

| Item | Laravel 7 | Laravel 13 |
|---|---|---|
| SQLite | Suportado | Suportado (usado neste projeto) |
| Query builder | `whereDate`, `whereTime`, etc. | Mesmos métodos, com melhorias de desempenho |
| Migrations | `Schema::create`, `Schema::table` | Mesmas APIs, com novas convenções de atributos |
| Factories | Closure-based classes | Classes com `definition()` e `HasFactory` |

---

## 7. Autenticação e Autorização

| Item | Laravel 7 | Laravel 13 |
|---|---|---|
| `make:auth` | Disponível | Removido (usar Breeze/Jetstream/Fortify) |
| Sanctum | Não existia (Laravel 8+) | Disponível |
| Passport | Disponível | Disponível |
| Gates e Policies | Suportados | Suportados (melhor integração com attributes) |

---

## 8. Novidades principais no Laravel 13 (não existiam no 7)

| Funcionalidade | Descrição |
|---|---|
| **PHP 8 Attributes** | `#[Fillable]`, `#[Hidden]`, etc., em vez de propriedades de classe |
| **Método `casts(): array`** | Substitui a propriedade `$casts` por um método mais flexível |
| **`bootstrap/app.php`** | Substitui os kernels HTTP e Console para configuração global |
| **Laravel Boost** | MCP server com ferramentas para assistentes de IA (`database-query`, `database-schema`, `search-docs`, etc.) |
| **Laravel Reverb** | Servidor WebSocket oficial (substitui necessidade de Pusher/Echo em muitos casos) |
| **Métodos `ensure`** | Novos métodos em coleções para garantir estado |
| **Melhorias de desempenho** | Query builder, routing e Blade mais rápidos |
| **Type hinting rigoroso** | Tipos de retorno e parâmetros mais estritos graças ao PHP 8.3+ |

---

## 9. Funcionalidades removidas ou depreciadas

| Item | Laravel 7 | Laravel 13 |
|---|---|---|
| `make:auth` | ✅ Disponível | ❌ Removido |
| `$casts` como propriedade | ✅ Padrão | ⚠️ Ainda funciona, mas `casts(): array` é recomendado |
| `app/Http/Kernel.php` | ✅ Existe | ❌ Removido |
| Laravel Mix | ✅ Padrão | ⚠️ Substituído por Vite |

---

## 10. Resumo das mudanças no projeto

Este projeto (Laravel 13.40.0) reflete as seguintes diferenças em relação a um projeto Laravel 7 típico:

1. **PHP 8.5** em vez de PHP 7.x — permite usar attributes, match expressions, typed properties, etc.
2. **Modelos com `#[Fillable]`** e `protected function casts(): array` em vez de propriedades `$fillable` e `$casts`.
3. **Sem `app/Http/Kernel.php`** — middleware e exceções configurados em `bootstrap/app.php`.
4. **Vite** para bundling de assets em vez de Laravel Mix.
5. **Laravel Boost** instalado como servidor MCP para ferramentas de IA.
6. **Base de dados SQLite** em vez de MySQL (o documento original menciona MySQL, mas o `.env` está configurado para SQLite).

---

## 11. Tabela comparativa resumida

| Categoria | Laravel 7 (2020) | Laravel 13 (2026) |
|---|---|---|
| PHP mínimo | 7.2.5 | 8.3 |
| PHP do projeto | — | 8.5.12 |
| `fillable` | Propriedade `$fillable` | Atributo `#[Fillable]` |
| `casts` | Propriedade `$casts` | Método `casts(): array` |
| Kernel | `app/Http/Kernel.php` | `bootstrap/app.php` |
| Assets | Laravel Mix | Vite |
| Auth scaffolding | `make:auth` | Breeze / Jetstream / Fortify |
| WebSockets | Pusher / Echo | Laravel Reverb |
| IA / MCP | Não | Laravel Boost |
| Versão deste projeto | — | 13.40.0 |

---

_Ficheiro gerado com base na documentação do projeto (`ESTRUTURA_DO_PROJETO.md`, `CLAUDE.md`, `AGENTS.md`) e na versão instalada do Laravel Framework._
