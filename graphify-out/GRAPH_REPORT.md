# Graph Report - laradocker  (2026-09-17)

## Corpus Check
- 40 files · ~11,903 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 26 file(s) not represented in the graph (top: (none) 15, .conf 3, .ini 3)

## Summary
- 196 nodes · 190 edges · 29 communities (13 shown, 16 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6c9c5029`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- composer.json
- package.json
- Production deployment
- Laravel Docker Application
- User
- UserFactory.php
- 0001_01_01_000000_create_users_table.php
- require-dev
- scripts
- config
- AppServiceProvider
- bootstrap/app.php
- Pest.php
- logging.php
- artisan
- console.php
- laravel-boost
- Controller.php
- entrypoint.sh
- Illuminate\Support\Facades\Route

## God Nodes (most connected - your core abstractions)
1. `Laravel Docker Application` - 12 edges
2. `require-dev` - 10 edges
3. `User` - 9 edges
4. `scripts` - 9 edges
5. `Основні команди` - 6 edges
6. `Production deployment` - 6 edges
7. `AppServiceProvider` - 5 edges
8. `config` - 5 edges
9. `UserFactory` - 5 edges
10. `Docker architecture` - 5 edges

## Surprising Connections (you probably didn't know these)
- None detected - all connections are within the same source files.

## Import Cycles
- None detected.

## Communities (29 total, 16 thin omitted)

### Community 0 - "composer.json"
Cohesion: 0.08
Nodes (23): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, keywords (+15 more)

### Community 1 - "package.json"
Cohesion: 0.09
Nodes (21): devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite, optionalDependencies, @laravel/multiplex (+13 more)

### Community 4 - "Production deployment"
Cohesion: 0.10
Nodes (16): Development mounts and production volumes, Docker architecture, Images, Multiple projects on one VPS, Networks and ports, Backup and restore, Deploy, External reverse proxy (+8 more)

### Community 5 - "Laravel Docker Application"
Cohesion: 0.11
Nodes (18): Backup MariaDB, Composer, npm і shell, Laravel Docker Application, Laravel і база даних, Production deployment, Reverse proxy та декілька проєктів на VPS, Vite, Документація (+10 more)

### Community 6 - "User"
Cohesion: 0.21
Nodes (9): User, DatabaseSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Seeder, Illuminate\Foundation\Auth\User (+1 more)

### Community 7 - "UserFactory.php"
Cohesion: 0.20
Nodes (6): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Facades\Hash, Illuminate\Support\Str, Pdo\Mysql, static

### Community 8 - "0001_01_01_000000_create_users_table.php"
Cohesion: 0.23
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 9 - "require-dev"
Cohesion: 0.20
Nodes (10): require-dev, fakerphp/faker, laravel/boost, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision (+2 more)

### Community 10 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 13 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 15 - "bootstrap/app.php"
Cohesion: 0.40
Nodes (4): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware, Illuminate\Http\Request

### Community 16 - "Pest.php"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, TestCase

### Community 17 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

## Knowledge Gaps
- **83 isolated node(s):** `php`, `Controller`, `$schema`, `name`, `type` (+78 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 127 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **16 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Laravel Docker Application` connect `Laravel Docker Application` to `Production deployment`?**
  _High betweenness centrality (0.024) - this node is a cross-community bridge._
- **Why does `require-dev` connect `require-dev` to `composer.json`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Why does `scripts` connect `scripts` to `composer.json`?**
  _High betweenness centrality (0.019) - this node is a cross-community bridge._
- **What connects `php`, `Controller`, `$schema` to the rest of the system?**
  _83 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.08333333333333333 - nodes in this community are weakly interconnected._
- **Should `package.json` be split into smaller, more focused modules?**
  _Cohesion score 0.09486166007905138 - nodes in this community are weakly interconnected._
- **Should `Production deployment` be split into smaller, more focused modules?**
  _Cohesion score 0.1 - nodes in this community are weakly interconnected._