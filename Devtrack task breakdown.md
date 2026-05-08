# 📋 DevTrack — Task Breakdown for Binome (Mariam & Zineb)

**Duration**: Monday 04/05/2026 – Friday 08/05/2026 (5 days)  
**Team**: Mariam (Backend) & Zineb (Backend)  
**Approach**: Feature-based distribution + Equal independence

---

## 🗂️ Task Distribution Overview

| Day | Mariam | Zineb |
|-----|-------|-------|
| **Mon** | Database + MCD/MLD | Auth Customization + Models |
| **Tue** | ProjectPolicy + ProjectController (CRUD) | TaskPolicy + TaskController (CRUD) |
| **Wed** | Project Members Management | Task Status Management + Form Requests |
| **Thu** | Project Archive/Restore + API Endpoint | Task API Endpoint |
| **Fri** | Tests + Debugging | Tests + Debugging |

---

# 🚀 DAY 1 - PROJECT SETUP & DATABASE FOUNDATION

## MARIAM - Task 1.1: Database Migrations & MCD/MLD Design

**Jira ID**: `DEVTRACK-01`  
**Title**: `[DATABASE] Create All Migrations & Design MCD/MLD`  
**Assignee**: Mariam  
**Type**: Task  
**Story Points**: 8  
**Priority**: Highest  
**Timeline**: Monday Morning & Afternoon (5 hours)

### Description

You'll design the complete data model for DevTrack (**MCD/MLD**) and create **all database migrations** based on that design. This foundational work gives Zineb everything she needs to customize the auth system. You're the database architect!

### Detailed Instructions

#### 1. **Design MCD (Conceptual Data Model) — No types, no foreign keys**

Create a diagram showing all **entities** and their **relationships**:

**Entities to model:**
- **User** (id, name, email, password, created_at, updated_at)
- **Project** (id, title, description, deadline, is_archived, created_at, updated_at)
- **Task** (id, title, description, deadline, status, priority, created_at, updated_at)
- **Project-User Relationship** (pivot table for many-to-many with role)

**Relationships:**
- User has many Projects (as lead)
- User has many Projects (as developer) — through pivot
- Project has many Tasks
- Task belongs to User (assigned_to)
- Task belongs to Project

**Draw this on**: Lucidchart, Draw.io, or paper then photograph

---

#### 2. **Design MLD (Logical Data Model) — With types, PK, FK**

Convert MCD to MLD with:
- **Table names** (users, projects, tasks, project_user)
- **Columns** with **types** (VARCHAR, INT, ENUM, TIMESTAMP, BOOLEAN)
- **Primary Keys** (PK)
- **Foreign Keys** (FK)
- **Pivot table structure** for many-to-many (id, user_id, project_id, role, created_at)

**Example structure for pivot:**
```
project_user:
  id (PK)
  user_id (FK) → users.id
  project_id (FK) → projects.id
  role (ENUM: 'lead', 'developer')
  created_at
  updated_at
```

**Table: tasks**
```
  id (PK)
  project_id (FK) → projects.id
  assigned_to (FK) → users.id
  title (VARCHAR)
  description (TEXT)
  deadline (DATETIME)
  status (ENUM: 'todo', 'in_progress', 'done')
  priority (ENUM: 'low', 'medium', 'high')
  created_at
  updated_at
```

---

#### 3. **Initialize Laravel Project**

```bash
# Create project
composer create-project laravel/laravel devtrack
cd devtrack

# Install Laravel Breeze for auth scaffolding
composer require laravel/breeze --dev
php artisan breeze:install blade

# Install debugging tools
composer require --dev laravel/debugbar
composer require --dev laravel/telescope

# Initialize Git
git init
git add .
git commit -m "[SETUP] Initial Laravel project with Breeze"
```

---

#### 4. **Create Users Migration** (Customize Breeze's default)

Breeze creates a basic users table. Customize/verify it:

```bash
php artisan make:migration create_users_table --create=users
```

**What columns to include:**
- id (PRIMARY KEY, auto-increment)
- name (VARCHAR)
- email (VARCHAR, UNIQUE)
- email_verified_at (TIMESTAMP, nullable)
- password (VARCHAR, hashed)
- remember_token (VARCHAR, nullable)
- created_at, updated_at (TIMESTAMPS)

**Key Point**: Do NOT add `role` here — role lives only in the **pivot table** (project_user)

---

#### 5. **Create Projects Migration**

```bash
php artisan make:migration create_projects_table --create=projects
```

**Columns** (based on MLD):
- id (PRIMARY KEY)
- title (VARCHAR)
- description (TEXT, nullable)
- deadline (DATETIME)
- created_at, updated_at (TIMESTAMPS)
- **created_by** (INTEGER, FK → users.id) — the Team Lead who created this project

**Key Point**: The `is_archived` column will come later with soft deletes

---

#### 6. **Create Tasks Migration**

```bash
php artisan make:migration create_tasks_table --create=tasks
```

**Columns** (based on MLD):
- id (PRIMARY KEY)
- project_id (INTEGER, FK → projects.id, onDelete: CASCADE)
- assigned_to (INTEGER, FK → users.id, nullable, onDelete: SET NULL)
- title (VARCHAR)
- description (TEXT, nullable)
- deadline (DATETIME)
- status (ENUM: 'todo', 'in_progress', 'done', default: 'todo')
- priority (ENUM: 'low', 'medium', 'high')
- created_at, updated_at (TIMESTAMPS)

**Key Points**:
- ENUM type depends on your MySQL version — some Laravel versions use STRING with `->enum()`
- Cascade delete: when project deleted, all tasks deleted
- `assigned_to` nullable because task can exist without assignee initially

---

#### 7. **Create Project-User Pivot Migration**

```bash
php artisan make:migration create_project_user_table --create=project_user
```

**Structure**:
- id (PRIMARY KEY)
- user_id (INTEGER, FK → users.id, onDelete: CASCADE)
- project_id (INTEGER, FK → projects.id, onDelete: CASCADE)
- role (ENUM: 'lead', 'developer', default: 'developer')
- created_at, updated_at (TIMESTAMPS)
- **COMPOSITE INDEX**: (user_id, project_id) — for faster lookups

**Key Points**:
- This is the many-to-many relationship table
- role column determines permissions (lead can edit/delete, developer can only update task status)
- Composite index prevents duplicate user-project combinations

---

#### 8. **Run All Migrations**

```bash
php artisan migrate
```

✅ If this passes, your schema is correctly structured

---

#### 9. **Create All Models**

```bash
php artisan make:model Project
php artisan make:model Task
# User model already exists from Breeze
```

For now, these are empty shells. Zineb will add relationships.

---

#### 10. **Project Directory Structure**

Verify this structure after setup:

```
app/
  Models/
    User.php
    Project.php
    Task.php
  Http/
    Controllers/
      ProjectController.php
      TaskController.php
    Requests/
      StoreProjectRequest.php
      UpdateProjectRequest.php
      (etc.)
  Policies/
    ProjectPolicy.php
    TaskPolicy.php

routes/
  web.php
  api.php

resources/
  views/
    layouts/
      app.blade.php
    projects/
    tasks/

database/
  migrations/
  seeders/

docs/
  MCD.png
  MLD.png
```

---

### What You'll Learn

📚 **Concepts**:
- **Relational Database Design** (MCD → MLD transformation)
- **Entity-Relationship Model** (One-to-Many, Many-to-Many)
- **Pivot Tables** (storing additional data in relationships)
- **Laravel Migrations** (creating tables programmatically)
- **Foreign Keys & Constraints** (referential integrity, cascade deletes)
- **ENUM Types** (storing fixed set of values)
- **Database Normalization** (designing for efficiency)
- **Project Architecture** (Models, Controllers, Routes separation)

📖 **Resources to review before**:
- [Database Design Basics](https://www.youtube.com/watch?v=QpdhBUYx7eE)
- [MCD/MLD Concepts](https://www.lucidchart.com/pages/er-diagrams)
- [Laravel Migrations](https://laravel.com/docs/migrations)
- [Foreign Keys in Laravel](https://laravel.com/docs/migrations#foreign-key-constraints)

---

### Acceptance Criteria

- ✅ MCD drawn with all 4 entities and relationships clearly labeled
- ✅ MLD exported with column types, PKs, FKs visible
- ✅ All 5 migrations created (users, projects, tasks, project_user, + soft_deletes later)
- ✅ `php artisan migrate` runs without errors
- ✅ All models created (User, Project, Task)
- ✅ Git initialized with clear commits:
  - `[SETUP] Initial Laravel project with Breeze`
  - `[DATABASE] Create all migrations (users, projects, tasks, project_user)`
  - `[DATABASE] Create User, Project, Task models`
- ✅ MCD/MLD files saved to `docs/` folder

---

### 🔗 **Deliverables**
- `docs/MCD.png` (or .pdf)
- `docs/MLD.png` (or .pdf)
- `database/migrations/` (all 5 files)
- `app/Models/User.php`, `Project.php`, `Task.php`
- GitHub repo with initial commits

---

---

## ZINEB - Task 1.2: Customize Laravel Breeze & Create Models

**Jira ID**: `DEVTRACK-02`  
**Title**: `[AUTH] Customize Breeze & Add User Model Relationships`  
**Assignee**: Zineb  
**Type**: Task  
**Story Points**: 5  
**Priority**: Highest  
**Timeline**: Monday Afternoon (2-3 hours)

### Description

You'll customize the **Laravel Breeze authentication** to fit DevTrack's structure by adding **User model relationships** and creating the **Project and Task models** (structure only). This prepares the foundation for the rest of the project.

### Detailed Instructions

#### 1. **Wait for Mariam's Migrations**

Before you start, wait for Mariam to:
- Complete all migrations (users, projects, tasks, project_user)
- Run `php artisan migrate` successfully
- Push migrations to Git

Once done, you can work in parallel on customization.

---

#### 2. **Review Mariam's MLD**

Look at the MLD diagram to understand:
- All table structures
- Column names and types
- Foreign key relationships
- The pivot table (project_user) structure

---

#### 3. **Customize User Model** 

Open `app/Models/User.php` (created by Breeze) and add these relationships:

```php
<?php

namespace App\Models;

// ... existing imports

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all projects where user is a member or lead
     * 
     * Relationship: Many-to-Many through pivot table project_user
     * Includes the 'role' column from pivot
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get all tasks assigned to this user
     * 
     * Relationship: One-to-Many
     */
    public function tasksAssigned()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    /**
     * Get all projects created by this user (as lead)
     * 
     * Relationship: One-to-Many
     */
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }
}
```

**What You're Adding**:
- `projects()` — shows projects user is member of (with role)
- `tasksAssigned()` — shows tasks assigned to this user
- `createdProjects()` — shows projects led by this user

**Why separate methods?**
- `projects()` includes developers AND leads (pivot table)
- `createdProjects()` only includes projects where user is lead (foreign key)
- Flexible querying in controllers

---

#### 4. **Create Project Model Structure**

Create `app/Models/Project.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    /**
     * Fields that can be mass-assigned
     * 
     * Protects against mass assignment vulnerabilities
     */
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'created_by',
    ];

    /**
     * Cast attributes to native types
     */
    protected $casts = [
        'deadline' => 'datetime',
    ];

    // === RELATIONSHIPS (to be implemented in later tasks) ===

    /**
     * Get the user who created this project
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all team members of this project
     * 
     * Many-to-Many through pivot table project_user
     * Includes role (lead or developer)
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get all tasks in this project
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
```

**Key Points**:
- `use SoftDeletes;` — enables archive feature (soft delete)
- `$fillable` — defines what can be mass-assigned
- `$casts` — automatically convert deadline to Carbon datetime
- All relationships are stub structure for now — will be fully used in later tasks

---

#### 5. **Create Task Model Structure**

Create `app/Models/Task.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * Fields that can be mass-assigned
     */
    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'deadline',
        'status',
        'priority',
    ];

    /**
     * Cast attributes to native types
     */
    protected $casts = [
        'deadline' => 'datetime',
    ];

    // === RELATIONSHIPS (to be implemented in later tasks) ===

    /**
     * Get the project this task belongs to
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user assigned to this task
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
```

---

#### 6. **Verify Breeze Installation**

Check that Breeze installed all auth scaffolding:

```bash
# Check if auth views exist
ls resources/views/auth
# Should see: confirm-password.blade.php, forgot-password.blade.php, login.blade.php, register.blade.php, reset-password.blade.php, verify-email.blade.php

# Check if auth routes exist
php artisan route:list | grep auth
# Should see: login, register, password.request, password.reset, password.email, etc.
```

---

#### 7. **Test Models**

Open Laravel Tinker to verify relationships work:

```bash
php artisan tinker

# Test creating a user
$user = User::first(); // Get existing user or create one
$user->name; // Should work

# Test relationships (even though no data yet)
$user->projects; // Should be empty collection
$user->tasksAssigned; // Should be empty collection
$user->createdProjects; // Should be empty collection

# Exit tinker
exit
```

---

#### 8. **Customize Breeze Layouts** (Optional but recommended)

Update `resources/views/layouts/app.blade.php` to reference DevTrack:

```blade
<!-- In the navigation, add links to projects -->
@auth
    <nav>
        <!-- ... existing nav ... -->
        <a href="{{ route('projects.index') }}">Projects</a>
    </nav>
@endauth
```

(You'll build out the full navbar in later tasks)

---

### What You'll Learn

📚 **Concepts**:
- **Eloquent Relationships** (One-to-Many, Many-to-Many)
- **withPivot()** — accessing extra columns in pivot table
- **withTimestamps()** — tracking when relationships were created
- **Naming Conventions** — foreign keys, relationship methods
- **Mass Assignment Protection** ($fillable)
- **Attribute Casting** (automatic type conversion)
- **Laravel Breeze Structure** (what auth it provides)
- **Model Organization** (where relationships live)

📖 **Resources**:
- [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)
- [One-to-Many Relationships](https://laravel.com/docs/eloquent-relationships#one-to-many)
- [Many-to-Many Relationships](https://laravel.com/docs/eloquent-relationships#many-to-many)
- [Breeze Documentation](https://laravel.com/docs/starter-kits#breeze-next)

---

### Acceptance Criteria

- ✅ User model updated with 3 relationships (projects, tasksAssigned, createdProjects)
- ✅ Project model created with $fillable and relationships
- ✅ Task model created with $fillable and relationships
- ✅ All models include helpful comments
- ✅ `php artisan tinker` shows relationships don't error
- ✅ Breeze auth scaffolding verified (routes exist)
- ✅ Git commits:
  - `[AUTH] Customize User model with project relationships`
  - `[MODEL] Create Project model with relationships`
  - `[MODEL] Create Task model with relationships`

---

### 🔗 **Deliverables**
- Updated `app/Models/User.php`
- `app/Models/Project.php`
- `app/Models/Task.php`
- GitHub commits

---

---

# 📅 DAY 2 - AUTHORIZATION POLICIES & PROJECT MANAGEMENT

## MARIAM - Task 2.1: ProjectPolicy & Authorization Logic

## MARIAM - Task 2.1: ProjectPolicy & Authorization Logic

**Jira ID**: `DEVTRACK-03`  
**Title**: `[AUTHORIZATION] Implement ProjectPolicy with Role-Based Access Control`  
**Assignee**: Mariam  
**Type**: Task  
**Story Points**: 8  
**Priority**: High  
**Timeline**: Tuesday Morning (4 hours)

### Description

You'll implement **complete authorization logic** for Projects using Laravel Policies. This ensures that:
- Only the **Project Lead** can edit/delete/archive projects
- Only **Project Members** can view projects
- Developers cannot perform lead-only actions

This is **critical security work** that prevents unauthorized access.

---

### Detailed Instructions

#### 1. **Understand Laravel Policies**

**What is a Policy?**
- A class that encapsulates authorization logic
- Methods return `true` (allowed) or `false` (denied)
- Used via `$this->authorize()` in controllers or `@can` in Blade

**Policy Methods** (Laravel convention):
```php
view($user, $model)      // Can this user VIEW the model?
create($user)            // Can this user CREATE new models?
update($user, $model)    // Can this user UPDATE the model?
delete($user, $model)    // Can this user DELETE the model?
restore($user, $model)   // Can this user RESTORE a soft-deleted model?
forceDelete($user, $model) // Can this user permanently DELETE the model?
```

---

#### 2. **Create ProjectPolicy**

```bash
php artisan make:policy ProjectPolicy --model=Project
```

This creates `app/Policies/ProjectPolicy.php`

---

#### 3. **Implement ProjectPolicy Methods**

**Method 1: `view($user, Project $project)`**

**Question**: Can this user view this project?

**Logic**:
- ✅ If user is the **lead** (created_by == user_id) → TRUE
- ✅ If user is a **member** (exists in project_user pivot with any role) → TRUE
- ❌ Otherwise → FALSE

**Why**: Only project members should see this project (privacy)

**Hint**: Use `$project->members()->where('user_id', $user->id)->exists()`

---

**Method 2: `create($user)`**

**Question**: Can this user create a NEW project?

**Logic**:
- ✅ Any authenticated user can create projects → TRUE

**Why**: In DevTrack, any team member can create a project and become its lead

---

**Method 3: `update($user, Project $project)`**

**Question**: Can this user modify this project?

**Logic**:
- ✅ Only the **lead** (created_by == user_id) → TRUE
- ❌ Developers cannot edit → FALSE

**Why**: Prevents developers from accidentally changing project scope

---

**Method 4: `delete($user, Project $project)`**

**Question**: Can this user delete this project?

**Logic**:
- ✅ Only the **lead** → TRUE
- ❌ Everyone else → FALSE

**Why**: Only project owner can delete

---

**Method 5: `restore($user, Project $project)`**

**Question**: Can this user restore an archived project?

**Logic**:
- ✅ Only the **lead** → TRUE

**Why**: For archive/restore feature (US5, US6)

---

**Method 6: `forceDelete($user, Project $project)`**

**Question**: Can this user permanently delete this project?

**Logic**:
- ✅ Only the **lead** → TRUE

**Why**: Bonus feature for permanent deletion from archives

---

#### 4. **Helper Method: isLead()**

Create a private helper method to avoid repeating the same check:

```php
private function isLead(User $user, Project $project): bool
{
    return $project->created_by === $user->id;
}

private function isMember(User $user, Project $project): bool
{
    return $project->members()->where('user_id', $user->id)->exists();
}
```

---

#### 5. **Register Policy in AuthServiceProvider**

Open `app/Providers/AuthServiceProvider.php`:

```php
use App\Models\Project;
use App\Policies\ProjectPolicy;

protected $policies = [
    Project::class => ProjectPolicy::class,
];
```

**Why**: Tells Laravel "when you see a Project, use ProjectPolicy"

---

#### 6. **Advanced: Before Method** (Optional but recommended)

Add this at the top of ProjectPolicy:

```php
public function before(User $user, $ability): bool|null
{
    // Admins bypass all checks (if you add admin role later)
    // if ($user->is_admin) {
    //     return true;
    // }
    
    // For now, return null (means "let the specific method decide")
    return null;
}
```

---

### What You'll Learn

📚 **Concepts**:
- **Authorization vs Authentication**
  - Authentication: "Who are you?" (login)
  - Authorization: "What can you do?" (Policies)
- **Role-Based Access Control (RBAC)** using pivot table `role` column
- **Policy Methods** convention and Laravel integration
- **Eloquent Queries in Policies** (checking relationships)
- **Security Best Practices** (fail-safe defaults, explicit permissions)
- **Debugging Authorization** with Telescope (shows policy checks)

📖 **Resources**:
- [Laravel Policies Documentation](https://laravel.com/docs/authorization#creating-policies)
- [Policy Actions Reference](https://laravel.com/docs/authorization#policy-methods)
- [Many-to-Many Queries](https://laravel.com/docs/eloquent-relationships#querying-many-to-many-relationships)

---

### Acceptance Criteria

- ✅ ProjectPolicy created with 6 methods (view, create, update, delete, restore, forceDelete)
- ✅ `isLead()` and `isMember()` helper methods implemented
- ✅ Each method has correct logic without `abort()` calls
- ✅ Policy registered in AuthServiceProvider
- ✅ No database queries in policy logic (use Eloquent correctly)
- ✅ Code has inline comments explaining each authorization rule
- ✅ Git commit: `[AUTHORIZATION] Implement ProjectPolicy with 6 authorization methods`

---

### Testing Your Policy (Manual)

You'll test this in later tasks with controllers, but for now:

```bash
php artisan tinker

# Create test user
$user = User::first();
$project = Project::first();

# Test policy manually
auth()->loginUsingId($user->id);
Gate::inspect('view', $project)->allowed(); // Should return true/false
```

---

### 🔗 **Deliverables**
- `app/Policies/ProjectPolicy.php`
- Updated `app/Providers/AuthServiceProvider.php`
- GitHub commit

---

---

## ZINEB - Task 2.2: TaskPolicy & Task Authorization

**Jira ID**: `DEVTRACK-04`  
**Title**: `[AUTHORIZATION] Implement TaskPolicy with Developer-Lead Separation`  
**Assignee**: Zineb  
**Type**: Task  
**Story Points**: 8  
**Priority**: High  
**Timeline**: Tuesday Morning (4 hours)

### Description

You'll implement **TaskPolicy** with different permissions for **Leads** and **Developers**:
- **Lead**: Can view, create, update, delete ANY task in their projects
- **Developer**: Can view tasks + **only update status** of their own assigned tasks

This is the core of the role-based separation in DevTrack.

---

### Detailed Instructions

#### 1. **Policy Authorization Matrix**

Before coding, understand the permission matrix:

| Action | Lead on Own Project | Developer on Own Project | Non-Member |
|--------|-----------------|----------------------|-----------|
| **View Task** | ✅ Yes | ✅ Yes (if assigned) | ❌ No |
| **Create Task** | ✅ Yes | ❌ No | ❌ No |
| **Edit Task** (full) | ✅ Yes | ❌ No | ❌ No |
| **Update Status** (only) | ✅ Yes | ✅ Only own tasks | ❌ No |
| **Delete Task** | ✅ Yes | ❌ No | ❌ No |

**Key insight**: Developer has limited permissions — security by least privilege

---

#### 2. **Create TaskPolicy**

```bash
php artisan make:policy TaskPolicy --model=Task
```

---

#### 3. **Implement TaskPolicy Methods**

**Method 1: `view($user, Task $task)`**

**Question**: Can this user view this task?

**Logic**:
- ✅ If user is the **lead** of the project → TRUE
- ✅ If user is **assigned to this task** → TRUE
- ✅ If user is a **member** of the project (any role) → TRUE
- ❌ Non-members → FALSE

**Hint**: `$task->project->members()->where('user_id', $user->id)->exists()`

---

**Method 2: `create($user, Project $project)`**

**Question**: Can this user create a task in this project?

**Note**: Unlike ProjectPolicy, you need the **Project** context, not just the user

```php
public function create(User $user, Project $project): bool
{
    // Only project lead can create tasks
    return $project->created_by === $user->id;
}
```

---

**Method 3: `update($user, Task $task)`**

**Question**: Can this user modify this task?

**Logic**:
- ✅ Only the **project lead** can update tasks
- ❌ Developers cannot → FALSE

**Hint**: Check `$task->project->created_by === $user->id`

---

**Method 4: `updateStatus($user, Task $task)`**

**Question**: Can this user change only the status?

**NEW METHOD** — this is different from `update()`

**Logic**:
- ✅ The **project lead** can update status → TRUE
- ✅ The **developer assigned to this task** can update status → TRUE
- ❌ Other developers or non-members → FALSE

**Why**: This allows developers limited access (status only, not other fields)

**Hint**: 
```php
if ($task->project->created_by === $user->id) return true; // Lead
if ($task->assigned_to === $user->id) return true; // Assigned dev
return false; // Others
```

---

**Method 5: `delete($user, Task $task)`**

**Question**: Can this user delete this task?

**Logic**:
- ✅ Only the **project lead** → TRUE
- ❌ Developers → FALSE

---

#### 4. **Helper Methods**

Create these in TaskPolicy:

```php
private function isProjectLead(User $user, Task $task): bool
{
    return $task->project->created_by === $user->id;
}

private function isAssignedDeveloper(User $user, Task $task): bool
{
    return $task->assigned_to === $user->id;
}

private function isProjectMember(User $user, Task $task): bool
{
    return $task->project->members()
        ->where('user_id', $user->id)
        ->exists();
}
```

---

#### 5. **Register TaskPolicy in AuthServiceProvider**

Open `app/Providers/AuthServiceProvider.php`:

```php
use App\Models\Task;
use App\Policies\TaskPolicy;

protected $policies = [
    Project::class => ProjectPolicy::class,
    Task::class => TaskPolicy::class,  // Add this line
];
```

---

#### 6. **Understand the Difference: update vs updateStatus**

In controllers, you'll use:

```php
// Lead wants to change deadline + assignee
$this->authorize('update', $task);  // Uses update() method

// Developer wants to change only status
$this->authorize('updateStatus', $task);  // Uses updateStatus() method
```

**Why separate methods?**
- Prevents accidental authorization bypass
- Clear intent in code
- Different validation rules

---

### What You'll Learn

📚 **Concepts**:
- **Custom Policy Methods** (beyond CRUD)
- **Role-Based vs Permission-Based Authorization**
- **Least Privilege Principle** (give minimum permissions needed)
- **Context-Aware Authorization** (Project + Task relationships)
- **Policy Method Signature Variations**
  - `view($user, $model)` — Model is required parameter
  - `create($user, $project)` — Context model instead
- **Telescope debugging** of authorization checks

📖 **Resources**:
- [Custom Policy Methods](https://laravel.com/docs/authorization#policy-methods)
- [Before & After Methods](https://laravel.com/docs/authorization#intercepting-policy-checks)
- [Authorizing Actions](https://laravel.com/docs/authorization#authorizing-actions)

---

### Acceptance Criteria

- ✅ TaskPolicy created with 5 methods (view, create, update, updateStatus, delete)
- ✅ Helper methods implemented (isProjectLead, isAssignedDeveloper, isProjectMember)
- ✅ Custom `updateStatus()` method allows developers to update only status
- ✅ Policy registered in AuthServiceProvider
- ✅ No hardcoded logic — uses helper methods consistently
- ✅ Each method has comments explaining the authorization rule
- ✅ Git commit: `[AUTHORIZATION] Implement TaskPolicy with role-based access control`

---

### 🔗 **Deliverables**
- `app/Policies/TaskPolicy.php`
- Updated `app/Providers/AuthServiceProvider.php`
- GitHub commit

---

---

# 🔧 DAY 2 (Afternoon) - PROJECT CONTROLLERS & CRUD

## MARIAM - Task 2.3: ProjectController - CRUD Operations

**Jira ID**: `DEVTRACK-05`  
**Title**: `[CONTROLLER] Implement ProjectController with Full CRUD & Authorization`  
**Assignee**: Mariam  
**Type**: Task  
**Story Points**: 13  
**Priority**: High  
**Timeline**: Tuesday Afternoon (4-5 hours)

### Description

You'll implement **ProjectController** with all CRUD operations (Create, Read, Update, Delete) while using **ProjectPolicy** for authorization. This handles US2, US3, US4, US5.

---

### Detailed Instructions

#### 1. **Generate ProjectController**

```bash
php artisan make:controller ProjectController --resource
```

This creates `app/Http/Controllers/ProjectController.php` with 7 empty methods:
- index() - List all projects
- create() - Show create form
- store() - Save new project
- show() - View single project
- edit() - Show edit form
- update() - Save updates
- destroy() - Delete project

---

#### 2. **Method 1: index() — Dashboard (US2)**

**Route**: GET `/projects`

**Purpose**: Show user's projects (as lead or developer)

**Instructions**:

```php
public function index()
{
    // Get all projects where user is a member (lead or developer)
    $projects = auth()->user()
        ->projects()  // From User model relationship
        ->with('tasks', 'members')  // Eager load to avoid N+1
        ->get();

    // Also add projects where user is the LEAD
    $ledProjects = Project::where('created_by', auth()->id())
        ->with('tasks', 'members')
        ->get();

    // Merge and remove duplicates (if user is both lead and member)
    $projects = $projects->merge($ledProjects)->unique('id');

    return view('projects.index', ['projects' => $projects]);
}
```

**What You'll Learn**:
- Accessing relationships from authenticated user
- `with()` for eager loading (prevents N+1)
- `unique()` for removing duplicates in collections

**Blade Template** (`resources/views/projects/index.blade.php`):
```blade
@foreach ($projects as $project)
    <div class="project-card">
        <h3>{{ $project->title }}</h3>
        <p>Deadline: {{ $project->deadline->format('Y-m-d') }}</p>
        <p>Tasks: {{ $project->tasks->count() }} total, 
           {{ $project->tasks->where('status', 'done')->count() }} done</p>
        
        @can('edit', $project)
            <a href="{{ route('projects.edit', $project) }}">Edit</a>
        @endcan
    </div>
@endforeach
```

---

#### 3. **Method 2: create() — Show Create Form**

**Route**: GET `/projects/create`

**Purpose**: Display form for creating new project

**Instructions**:

```php
public function create()
{
    // Any user can create, so no authorization needed
    return view('projects.create');
}
```

**Form** (`resources/views/projects/create.blade.php`):
```blade
<form action="{{ route('projects.store') }}" method="POST">
    @csrf
    
    <input type="text" name="title" placeholder="Project Title" required>
    <textarea name="description" placeholder="Description"></textarea>
    <input type="datetime-local" name="deadline" required>
    
    <button type="submit">Create Project</button>
</form>
```

---

#### 4. **Method 3: store() — Save New Project (US3)**

**Route**: POST `/projects`

**Purpose**: Create project and save to database

**Instructions**:

```php
public function store(StoreProjectRequest $request)
{
    // Authorization: user already proved they can create (by reaching this method)
    // But let's be explicit
    $this->authorize('create', Project::class);

    // Create project with current user as creator
    $project = Project::create([
        'title' => $request->validated()['title'],
        'description' => $request->validated()['description'],
        'deadline' => $request->validated()['deadline'],
        'created_by' => auth()->id(),
    ]);

    // Add user to project as LEAD
    $project->members()->attach(auth()->id(), ['role' => 'lead']);

    return redirect()
        ->route('projects.show', $project)
        ->with('success', 'Project created successfully');
}
```

**What You'll Learn**:
- Form Request validation (StoreProjectRequest — you'll create this next task)
- `attach()` method for many-to-many relationships
- Attaching pivot data (role) during attach
- Flash message session

**Note**: `StoreProjectRequest` is created by Zineb (Task 2.4)

---

#### 5. **Method 4: show() — View Single Project (US2)**

**Route**: GET `/projects/{project}`

**Purpose**: Display project details and tasks

**Instructions**:

```php
public function show(Project $project)
{
    // Check authorization using Policy
    $this->authorize('view', $project);

    // Load relationships
    $project->load('tasks', 'members');

    return view('projects.show', ['project' => $project]);
}
```

**Template** (`resources/views/projects/show.blade.php`):
```blade
<h1>{{ $project->title }}</h1>
<p>{{ $project->description }}</p>
<p>Deadline: {{ $project->deadline->format('Y-m-d') }}</p>

<h2>Team Members</h2>
<ul>
    @foreach ($project->members as $member)
        <li>{{ $member->name }} ({{ $member->pivot->role }})</li>
    @endforeach
</ul>

<h2>Tasks</h2>
@foreach ($project->tasks as $task)
    <div>{{ $task->title }} - {{ $task->status }}</div>
@endforeach
```

**Key Point**: `$member->pivot->role` accesses data from pivot table

---

#### 6. **Method 5: edit() — Show Edit Form (US4)**

**Route**: GET `/projects/{project}/edit`

**Purpose**: Display form for editing project

**Instructions**:

```php
public function edit(Project $project)
{
    $this->authorize('update', $project);
    
    return view('projects.edit', ['project' => $project]);
}
```

**Template** (`resources/views/projects/edit.blade.php`):
```blade
<form action="{{ route('projects.update', $project) }}" method="POST">
    @csrf
    @method('PUT')
    
    <input type="text" name="title" value="{{ $project->title }}" required>
    <textarea name="description">{{ $project->description }}</textarea>
    <input type="datetime-local" name="deadline" value="{{ $project->deadline }}" required>
    
    <button type="submit">Update Project</button>
</form>
```

**Key Point**: `@method('PUT')` spoofs HTTP method (forms only support GET/POST)

---

#### 7. **Method 6: update() — Save Updates (US4)**

**Route**: PUT `/projects/{project}`

**Purpose**: Update project

**Instructions**:

```php
public function update(UpdateProjectRequest $request, Project $project)
{
    $this->authorize('update', $project);

    $project->update($request->validated());

    return redirect()
        ->route('projects.show', $project)
        ->with('success', 'Project updated');
}
```

---

#### 8. **Method 7: destroy() — Archive Project (US5)**

**Route**: DELETE `/projects/{project}`

**Purpose**: Archive (soft delete) the project

**Instructions**:

```php
public function destroy(Project $project)
{
    $this->authorize('delete', $project);

    // Soft delete (archive)
    $project->delete();

    return redirect()
        ->route('projects.index')
        ->with('success', 'Project archived');
}
```

**Note**: This uses Laravel Soft Deletes — the record is marked `deleted_at` but not removed from DB

---

#### 9. **Register Routes**

Open `routes/web.php`:

```php
Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);
});
```

This automatically creates:
- GET `/projects` → index
- GET `/projects/create` → create
- POST `/projects` → store
- GET `/projects/{project}` → show
- GET `/projects/{project}/edit` → edit
- PUT `/projects/{project}` → update
- DELETE `/projects/{project}` → destroy

---

### What You'll Learn

📚 **Concepts**:
- **RESTful Routing** (resource routes, HTTP methods)
- **Controller Authorization** using `$this->authorize()`
- **Request Validation** via Form Requests
- **Eager Loading** with `with()` (N+1 prevention)
- **Soft Deletes** (delete that keeps data)
- **Pivot Table Access** (attaching and reading)
- **Flash Messages** (session feedback)
- **Blade Template Syntax** (directives like @csrf, @method, @can)

📖 **Resources**:
- [RESTful Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Authorization in Controllers](https://laravel.com/docs/authorization#authorizing-actions)
- [Soft Deletes](https://laravel.com/docs/eloquent#soft-deleting)

---

### Acceptance Criteria

- ✅ ProjectController created with 7 methods
- ✅ Every method uses `$this->authorize()` where needed
- ✅ All methods use `with()` for eager loading
- ✅ Routes registered as resource route
- ✅ Form methods use `@csrf` and `@method()` directives
- ✅ Pivot table accessed correctly with `->pivot->role`
- ✅ Git commits:
  - `[CONTROLLER] Implement ProjectController index & create`
  - `[CONTROLLER] Implement ProjectController store, show, edit, update`
  - `[CONTROLLER] Implement ProjectController destroy (archive)`

---

### 🔗 **Deliverables**
- `app/Http/Controllers/ProjectController.php`
- `resources/views/projects/` (index, create, edit, show)
- Updated `routes/web.php`
- GitHub commits

---

---

# 🔧 DAY 2 (Afternoon) - TASK CONTROLLER & CRUD

## ZINEB - Task 2.4: TaskController - CRUD with Status-Only Update for Developers

**Jira ID**: `DEVTRACK-06`  
**Title**: `[CONTROLLER] Implement TaskController with Role-Based CRUD`  
**Assignee**: Zineb  
**Type**: Task  
**Story Points**: 13  
**Priority**: High  
**Timeline**: Tuesday Afternoon (4-5 hours)

### Description

You'll implement **TaskController** with special handling for developers — they can only update task **status**, not other fields. This implements US8, US9, US10, US11, US12.

---

### Detailed Instructions

#### 1. **Generate TaskController**

```bash
php artisan make:controller TaskController --resource
```

---

#### 2. **Method 1: index() — List Project Tasks (US8)**

**Route**: GET `/projects/{project}/tasks`

**Purpose**: Show all tasks for a project

**Instructions**:

```php
public function index(Project $project)
{
    // User must be able to view the project
    $this->authorize('view', $project);

    // Load tasks with eager loading
    $tasks = $project->tasks()
        ->with('assignee', 'project')
        ->orderBy('deadline')
        ->get();

    return view('tasks.index', [
        'project' => $project,
        'tasks' => $tasks,
    ]);
}
```

**Template** (`resources/views/tasks/index.blade.php`):
```blade
<h1>{{ $project->title }} - Tasks</h1>

@foreach ($tasks as $task)
    <div class="task-card">
        <h3>{{ $task->title }}</h3>
        <p>Status: {{ $task->status_label }}</p> {{-- Use accessor --}}
        <p>Priority: {{ $task->priority }}</p>
        <p>Assigned to: {{ $task->assignee?->name ?? 'Unassigned' }}</p>
        <p>Deadline: {{ $task->deadline->format('Y-m-d') }}</p>
        
        @can('updateStatus', $task)
            <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                @csrf
                <select name="status">
                    <option value="todo">To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
                <button type="submit">Update Status</button>
            </form>
        @endcan
    </div>
@endforeach
```

**Accessors**: We'll create `status_label` accessor in Task model (Task 3.2)

---

#### 3. **Method 2: create() — Show Create Form (US9)**

**Route**: GET `/projects/{project}/tasks/create`

**Purpose**: Show form for creating task

**Instructions**:

```php
public function create(Project $project)
{
    $this->authorize('create', [Task::class, $project]);

    // Get project members for assignment dropdown
    $members = $project->members()->get();

    return view('tasks.create', [
        'project' => $project,
        'members' => $members,
    ]);
}
```

**Template** (`resources/views/tasks/create.blade.php`):
```blade
<form action="{{ route('tasks.store', $project) }}" method="POST">
    @csrf
    
    <input type="text" name="title" placeholder="Task Title" required>
    <textarea name="description" placeholder="Description"></textarea>
    <input type="datetime-local" name="deadline" required>
    
    <select name="priority">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
    </select>
    
    <select name="assigned_to">
        <option value="">Unassigned</option>
        @foreach ($members as $member)
            <option value="{{ $member->id }}">{{ $member->name }}</option>
        @endforeach
    </select>
    
    <button type="submit">Create Task</button>
</form>
```

---

#### 4. **Method 3: store() — Save New Task (US9)**

**Route**: POST `/projects/{project}/tasks`

**Instructions**:

```php
public function store(StoreTaskRequest $request, Project $project)
{
    $this->authorize('create', [Task::class, $project]);

    $task = Task::create([
        'project_id' => $project->id,
        'title' => $request->validated()['title'],
        'description' => $request->validated()['description'],
        'deadline' => $request->validated()['deadline'],
        'priority' => $request->validated()['priority'],
        'assigned_to' => $request->validated()['assigned_to'],
        'status' => 'todo', // Default status
    ]);

    return redirect()
        ->route('tasks.show', [$project, $task])
        ->with('success', 'Task created');
}
```

---

#### 5. **Method 4: show() — View Single Task (US8)**

**Route**: GET `/projects/{project}/tasks/{task}`

**Instructions**:

```php
public function show(Project $project, Task $task)
{
    $this->authorize('view', $task);

    $task->load('assignee', 'project');

    return view('tasks.show', [
        'project' => $project,
        'task' => $task,
    ]);
}
```

---

#### 6. **Method 5: edit() — Show Edit Form (US10)**

**Route**: GET `/projects/{project}/tasks/{task}/edit`

**Purpose**: Show form for editing task

**Instructions**:

```php
public function edit(Project $project, Task $task)
{
    $this->authorize('update', $task);

    $members = $project->members()->get();

    return view('tasks.edit', [
        'project' => $project,
        'task' => $task,
        'members' => $members,
    ]);
}
```

---

#### 7. **Method 6: update() — Save Full Updates (US10)**

**Route**: PUT `/projects/{project}/tasks/{task}`

**Purpose**: Update all task fields (lead only)

**Instructions**:

```php
public function update(UpdateTaskRequest $request, Project $project, Task $task)
{
    $this->authorize('update', $task);

    $task->update($request->validated());

    return redirect()
        ->route('tasks.show', [$project, $task])
        ->with('success', 'Task updated');
}
```

---

#### 8. **Method 7: updateStatus() — Update Status Only (US11)**

**Route**: PUT `/projects/{project}/tasks/{task}/status` (or POST `/tasks/{task}/status`)

**Purpose**: Allow developers to update ONLY the status field

**This is the key difference** — developers use this, leads use `update()`

**Instructions**:

```php
public function updateStatus(Request $request, Project $project, Task $task)
{
    // Use TaskPolicy's custom method
    $this->authorize('updateStatus', $task);

    // Validate ONLY the status field
    $validated = $request->validate([
        'status' => 'required|in:todo,in_progress,done',
    ]);

    $task->update(['status' => $validated['status']]);

    return redirect()
        ->route('tasks.show', [$project, $task])
        ->with('success', 'Task status updated');
}
```

**Why separate method?**
- Developer cannot access `update()` (policy denies)
- Developer can only access `updateStatus()` (policy allows)
- Clear separation of concerns

**Route for this** (in `routes/web.php`):
```php
Route::put('/projects/{project}/tasks/{task}/status', 
    [TaskController::class, 'updateStatus'])
    ->name('tasks.updateStatus');
```

---

#### 9. **Method 8: destroy() — Delete Task (US12)**

**Route**: DELETE `/projects/{project}/tasks/{task}`

**Instructions**:

```php
public function destroy(Project $project, Task $task)
{
    $this->authorize('delete', $task);

    $task->delete();

    return redirect()
        ->route('tasks.index', $project)
        ->with('success', 'Task deleted');
}
```

---

#### 10. **Route Registration**

Open `routes/web.php`:

```php
Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);
    
    // Nested routes: tasks belong to projects
    Route::resource('projects.tasks', TaskController::class);
    
    // Custom route for status-only updates
    Route::put('projects/{project}/tasks/{task}/status', 
        [TaskController::class, 'updateStatus'])
        ->name('tasks.updateStatus');
});
```

**Key Point**: Nested routes create URLs like:
- `/projects/{project}/tasks` (index)
- `/projects/{project}/tasks/create` (create)
- `/projects/{project}/tasks/{task}` (show)
- `/projects/{project}/tasks/{task}/edit` (edit)
- `/projects/{project}/tasks/{task}` (update/delete)

---

### What You'll Learn

📚 **Concepts**:
- **Nested Resources** in Laravel (project → tasks hierarchy)
- **Custom Route Methods** (updateStatus beyond CRUD)
- **Custom Policy Methods** usage in controllers
- **Request Validation** in controller vs Form Request
- **Single-field Updates** (mass assignment security)
- **Dependent Authorization** (passing context to policies)
- **Template Conditionals** (@can directive with custom methods)

📖 **Resources**:
- [Nested Resource Controllers](https://laravel.com/docs/controllers#restful-nested-resources)
- [Custom Policy Methods](https://laravel.com/docs/authorization#policy-methods)
- [Mass Assignment](https://laravel.com/docs/eloquent#mass-assignment)

---

### Acceptance Criteria

- ✅ TaskController created with 8 methods (index, create, store, show, edit, update, updateStatus, destroy)
- ✅ `updateStatus()` uses custom TaskPolicy method
- ✅ All methods authorize correctly
- ✅ Nested routes configured properly
- ✅ Eager loading with `with()` on all methods
- ✅ Form templates use @csrf, @method, @can directives
- ✅ Status dropdown in template with correct values
- ✅ Git commits:
  - `[CONTROLLER] Implement TaskController index & create`
  - `[CONTROLLER] Implement TaskController store, show, edit, update`
  - `[CONTROLLER] Implement TaskController updateStatus (developer-only)`
  - `[CONTROLLER] Implement TaskController destroy`

---

### 🔗 **Deliverables**
- `app/Http/Controllers/TaskController.php`
- `resources/views/tasks/` (index, create, edit, show)
- Updated `routes/web.php`
- GitHub commits

---

---

# ✅ DAY 3 - FORM REQUESTS & DATA VALIDATION

## MARIAM - Task 3.1: Form Requests for Projects

**Jira ID**: `DEVTRACK-07`  
**Title**: `[VALIDATION] Create StoreProjectRequest & UpdateProjectRequest`  
**Assignee**: Mariam  
**Type**: Task  
**Story Points**: 5  
**Priority**: High  
**Timeline**: Wednesday Morning (2 hours)

### Description

You'll create **Form Request classes** that handle **validation** for project creation and updates. This keeps controllers clean and validation rules centralized.

---

### Detailed Instructions

#### 1. **Create StoreProjectRequest**

```bash
php artisan make:request StoreProjectRequest
```

Opens `app/Http/Requests/StoreProjectRequest.php`

---

#### 2. **Implement StoreProjectRequest**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * Note: We already check in controller with $this->authorize('create', Project::class)
     * So this just returns true. But good practice to have it.
     */
    public function authorize(): bool
    {
        return true; // Already authorized in controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'description' => 'nullable|string|max:1000',
            'deadline' => 'required|date_format:Y-m-d\TH:i|after:now',
        ];
    }

    /**
     * Custom error messages (optional but recommended)
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Project title is required',
            'title.min' => 'Title must be at least 3 characters',
            'deadline.after' => 'Deadline must be in the future',
        ];
    }
}
```

**Rules Explanation**:
- `title` 
  - `required` — user must provide
  - `string` — must be text (not array/file)
  - `max:255` — max 255 characters
  - `min:3` — at least 3 characters
- `description`
  - `nullable` — can be empty
  - `string` — if provided, must be text
  - `max:1000` — max 1000 chars
- `deadline`
  - `required` — must provide
  - `date_format:Y-m-d\TH:i` — matches HTML datetime-local input format
  - `after:now` — must be future date (security: prevent past deadlines)

---

#### 3. **Create UpdateProjectRequest**

```bash
php artisan make:request UpdateProjectRequest
```

---

#### 4. **Implement UpdateProjectRequest**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Controller already authorizes with $this->authorize('update', $project)
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'description' => 'nullable|string|max:1000',
            'deadline' => 'required|date_format:Y-m-d\TH:i|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Project title is required',
            'deadline.after' => 'Deadline must be in the future',
        ];
    }
}
```

**Note**: Same rules as Store — can be refactored if rules diverge later

---

#### 5. **Update ProjectController to Use These**

In `ProjectController.php`, update store():

```php
use App\Http\Requests\StoreProjectRequest;

public function store(StoreProjectRequest $request)
{
    // Validation already happened! $request->validated() is safe
    $project = Project::create([
        'title' => $request->validated()['title'],
        'description' => $request->validated()['description'] ?? null,
        'deadline' => $request->validated()['deadline'],
        'created_by' => auth()->id(),
    ]);

    $project->members()->attach(auth()->id(), ['role' => 'lead']);

    return redirect()
        ->route('projects.show', $project)
        ->with('success', 'Project created successfully');
}
```

And update():

```php
use App\Http\Requests\UpdateProjectRequest;

public function update(UpdateProjectRequest $request, Project $project)
{
    $this->authorize('update', $project);

    $project->update($request->validated());

    return redirect()
        ->route('projects.show', $project)
        ->with('success', 'Project updated');
}
```

---

### What You'll Learn

📚 **Concepts**:
- **Form Request Classes** (encapsulate validation logic)
- **Separation of Concerns** (validation out of controller)
- **Validation Rules** (required, string, max, date_format, after)
- **Custom Error Messages** (user-friendly validation feedback)
- **Request Authorization** (Form Request level, though we use controller level)
- **Data Validation before Database** (prevent invalid data)

📖 **Resources**:
- [Laravel Form Requests](https://laravel.com/docs/validation#form-request-validation)
- [Validation Rules Reference](https://laravel.com/docs/validation#available-validation-rules)

---

### Acceptance Criteria

- ✅ StoreProjectRequest created with rules() and messages()
- ✅ UpdateProjectRequest created with rules() and messages()
- ✅ Rules prevent past deadlines with `after:now`
- ✅ Error messages are user-friendly (in messages())
- ✅ ProjectController.store() and update() use Form Requests
- ✅ `$request->validated()` used instead of `$request->all()`
- ✅ Git commit: `[VALIDATION] Create Form Requests for Projects`

---

### 🔗 **Deliverables**
- `app/Http/Requests/StoreProjectRequest.php`
- `app/Http/Requests/UpdateProjectRequest.php`
- Updated `app/Http/Controllers/ProjectController.php`
- GitHub commit

---

---

## ZINEB - Task 3.2: Form Requests for Tasks + Accessors

**Jira ID**: `DEVTRACK-08`  
**Title**: `[VALIDATION] Create StoreTaskRequest & UpdateTaskRequest + Accessors`  
**Assignee**: Zineb  
**Type**: Task  
**Story Points**: 8  
**Priority**: High  
**Timeline**: Wednesday Morning (3 hours)

### Description

You'll create **Form Requests for Tasks** and add **Accessors** to the Task model for formatting status and deadline urgency. Accessors transform data before display.

---

### Detailed Instructions

#### 1. **Create StoreTaskRequest**

```bash
php artisan make:request StoreTaskRequest
```

---

#### 2. **Implement StoreTaskRequest**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization handled in controller
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'description' => 'nullable|string|max:2000',
            'deadline' => 'required|date_format:Y-m-d\TH:i|after:now',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required',
            'priority.in' => 'Priority must be low, medium, or high',
            'assigned_to.exists' => 'Selected developer does not exist',
        ];
    }
}
```

**Rules Explanation**:
- `assigned_to`
  - `nullable` — can be empty (unassigned task)
  - `integer` — if provided, must be a number
  - `exists:users,id` — if provided, must be a valid user ID

---

#### 3. **Create UpdateTaskRequest**

```bash
php artisan make:request UpdateTaskRequest
```

---

#### 4. **Implement UpdateTaskRequest**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'description' => 'nullable|string|max:2000',
            'deadline' => 'required|date_format:Y-m-d\TH:i|after:now',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required',
            'priority.in' => 'Priority must be low, medium, or high',
            'assigned_to.exists' => 'Selected developer does not exist',
        ];
    }
}
```

---

#### 5. **Add Accessors to Task Model**

Accessors **read** data and transform it. Open `app/Models/Task.php`:

---

##### Accessor 1: `status_label`

**Purpose**: Transform status (todo, in_progress, done) into human-readable labels

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'deadline',
        'status',
        'priority',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Accessor: Transform status to human-readable label
     * 
     * Access in views: {{ $task->status_label }}
     * Value in DB: 'todo' → Label: 'To Do'
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function () {
                $labels = [
                    'todo' => 'To Do',
                    'in_progress' => 'In Progress',
                    'done' => 'Done',
                ];
                return $labels[$this->status] ?? $this->status;
            }
        );
    }

    // Relationships...
}
```

**How it works**:
- Database stores: `'todo'`, `'in_progress'`, `'done'`
- In views/API, when you access `$task->status_label`, it returns: `'To Do'`, `'In Progress'`, `'Done'`
- Original `$task->status` remains unchanged

**Usage in Template**:
```blade
<p>Status: {{ $task->status_label }}</p>
<!-- Displays: Status: In Progress -->
```

---

##### Accessor 2: `deadline_status` (Bonus)

**Purpose**: Show urgency indicator based on deadline

```php
protected function deadlineStatus(): Attribute
{
    return Attribute::make(
        get: function () {
            $now = now();
            $deadline = $this->deadline;
            
            // Only show urgent if task is not done
            if ($this->status === 'done') {
                return 'completed';
            }
            
            // Days until deadline
            $daysLeft = $now->diffInDays($deadline, false);
            
            if ($daysLeft < 0) {
                return 'overdue';
            } elseif ($daysLeft <= 1) {
                return 'urgent';
            } elseif ($daysLeft <= 3) {
                return 'soon';
            } else {
                return 'on-track';
            }
        }
    );
}
```

**Usage in Template**:
```blade
<span class="deadline-{{ $task->deadline_status }}">
    {{ $task->deadline_status }}
</span>
<!-- Displays: urgent, soon, on-track, overdue, completed -->
```

**CSS Example**:
```css
.deadline-urgent { color: red; }
.deadline-soon { color: orange; }
.deadline-on-track { color: green; }
.deadline-overdue { color: darkred; font-weight: bold; }
```

---

#### 6. **Update Task Views to Use Accessors**

In `resources/views/tasks/index.blade.php`:

```blade
@foreach ($tasks as $task)
    <div class="task-card deadline-{{ $task->deadline_status }}">
        <h3>{{ $task->title }}</h3>
        <p>Status: <strong>{{ $task->status_label }}</strong></p>
        <p>Priority: {{ $task->priority }}</p>
        <p>Assigned to: {{ $task->assignee?->name ?? 'Unassigned' }}</p>
        <p>Deadline: {{ $task->deadline->format('Y-m-d') }}
           <span class="deadline-{{ $task->deadline_status }}">
               {{ $task->deadline_status }}
           </span>
        </p>
    </div>
@endforeach
```

---

### What You'll Learn

📚 **Concepts**:
- **Accessors (Mutators Part 1)** — transform data when reading from model
- **Laravel 8+ Accessor Syntax** using `Attribute::make()`
- **Conditional Logic in Accessors** (based on multiple fields)
- **Date Calculations** (`diffInDays()`)
- **Separation of Formatting** (logic in model, not template)
- **DRY Principle** (reusable accessors across views/API)

📖 **Resources**:
- [Laravel Accessors & Mutators](https://laravel.com/docs/eloquent-mutators#accessors-and-mutators)
- [Attribute Class](https://laravel.com/docs/eloquent-mutators#attribute-objects)
- [Date Methods](https://laravel.com/docs/eloquent-casting#date-casting)

---

### Acceptance Criteria

- ✅ StoreTaskRequest created with validation rules
- ✅ UpdateTaskRequest created with same rules
- ✅ `status_label` accessor returns human-readable labels
- ✅ `deadline_status` accessor (or similar) returns urgency
- ✅ TaskController uses Form Requests
- ✅ Accessors used in templates ({{ $task->status_label }})
- ✅ Accessors used in API Resource (we'll do next)
- ✅ No logic in templates — all in model
- ✅ Git commits:
  - `[VALIDATION] Create Form Requests for Tasks`
  - `[MODEL] Add status_label and deadline_status accessors to Task`

---

### 🔗 **Deliverables**
- `app/Http/Requests/StoreTaskRequest.php`
- `app/Http/Requests/UpdateTaskRequest.php`
- Updated `app/Models/Task.php` with accessors
- Updated task views using accessors
- GitHub commits

---

---

# 🌐 DAY 4 - API ENDPOINT & MEMBERS MANAGEMENT

## MARIAM - Task 4.1: Project Members Management

**Jira ID**: `DEVTRACK-09`  
**Title**: `[FEATURE] Implement Add/Remove Project Members (US7)`  
**Assignee**: Mariam  
**Type**: Task  
**Story Points**: 8  
**Priority**: High  
**Timeline**: Thursday Morning (3 hours)

### Description

You'll implement the ability for Team Leads to **add developers to projects** (by email) and **remove them** — implementing US7.

---

### Detailed Instructions

#### 1. **Add Methods to ProjectController**

#### Method 1: Show Members Form

```php
public function showMembers(Project $project)
{
    $this->authorize('update', $project);

    $members = $project->members()->get();

    return view('projects.members', [
        'project' => $project,
        'members' => $members,
    ]);
}
```

---

#### Method 2: Add Member

```php
public function addMember(Request $request, Project $project)
{
    $this->authorize('update', $project);

    // Validate input
    $validated = $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    // Find user by email
    $user = User::where('email', $validated['email'])->firstOrFail();

    // Check if already a member
    if ($project->members()->where('user_id', $user->id)->exists()) {
        return back()->with('warning', 'User is already a member of this project');
    }

    // Add as developer
    $project->members()->attach($user->id, ['role' => 'developer']);

    return back()->with('success', "{$user->name} added as developer");
}
```

**Key Points**:
- `exists:users,email` — validate the email actually exists in system
- `attach()` — add to many-to-many relationship
- Default role: `'developer'`

---

#### Method 3: Remove Member

```php
public function removeMember(Request $request, Project $project)
{
    $this->authorize('update', $project);

    $validated = $request->validate([
        'user_id' => 'required|integer|exists:users,id',
    ]);

    $user = User::find($validated['user_id']);

    // Prevent removing the lead
    if ($user->id === $project->created_by) {
        return back()->with('error', 'Cannot remove the project lead');
    }

    // Remove from project
    $project->members()->detach($user->id);

    return back()->with('success', "{$user->name} removed from project");
}
```

**Key Points**:
- `detach()` — remove from many-to-many relationship
- Prevent removing lead (business logic)

---

#### 2. **Add Routes**

In `routes/web.php`:

```php
Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);
    
    // Member management
    Route::get('/projects/{project}/members', [ProjectController::class, 'showMembers'])
        ->name('projects.members.show');
    
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])
        ->name('projects.members.add');
    
    Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])
        ->name('projects.members.remove');
});
```

---

#### 3. **Create Members View**

Create `resources/views/projects/members.blade.php`:

```blade
<h1>{{ $project->title }} - Manage Members</h1>

<!-- Add Member Form -->
<div class="add-member-section">
    <h2>Add Team Member</h2>
    <form action="{{ route('projects.members.add', $project) }}" method="POST">
        @csrf
        
        <label>Developer Email</label>
        <input type="email" name="email" placeholder="developer@example.com" required>
        
        @error('email')
            <span class="error">{{ $message }}</span>
        @enderror
        
        <button type="submit">Add Member</button>
    </form>
</div>

<!-- Current Members List -->
<div class="members-section">
    <h2>Team Members</h2>
    
    @if ($members->isEmpty())
        <p>No members yet</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <strong>{{ ucfirst($member->pivot->role) }}</strong>
                        </td>
                        <td>
                            @if ($member->id !== $project->created_by)
                                <form action="{{ route('projects.members.remove', [$project, $member]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger">Remove</button>
                                </form>
                            @else
                                <span class="badge">Project Lead</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
```

---

### What You'll Learn

📚 **Concepts**:
- **Many-to-Many Operations** (attach, detach, exists)
- **Validation Rules** (exists, email)
- **Business Logic in Controllers** (preventing lead removal)
- **Form Deletion Workaround** (@method('DELETE'))
- **Pivot Table Access** ($member->pivot->role)
- **User Feedback** (with() messages)

---

### Acceptance Criteria

- ✅ ProjectController.addMember() validates email exists
- ✅ ProjectController.removeMember() prevents removing lead
- ✅ Routes registered for show, add, remove
- ✅ Members template displays current members with roles
- ✅ Add member form with email input
- ✅ Remove button with confirmation (optional)
- ✅ Git commits:
  - `[FEATURE] Implement add/remove project members`

---

### 🔗 **Deliverables**
- Updated `app/Http/Controllers/ProjectController.php`
- `resources/views/projects/members.blade.php`
- Updated `routes/web.php`
- GitHub commit

---

---

## ZINEB - Task 4.2: API Endpoint for Tasks + TaskResource

**Jira ID**: `DEVTRACK-10`  
**Title**: `[API] Implement GET /api/projects/{project}/tasks Endpoint (US13)`  
**Assignee**: Zineb  
**Type**: Task  
**Story Points**: 8  
**Priority**: High  
**Timeline**: Thursday Morning (3 hours)

### Description

You'll create an **API endpoint** that returns project tasks as JSON, using **Resource classes** for formatting. This implements US13.

---

### Detailed Instructions

#### 1. **Create TaskResource**

A Resource is a transformation layer that formats model data for API responses.

```bash
php artisan make:resource TaskResource
```

---

#### 2. **Implement TaskResource**

Open `app/Http/Resources/TaskResource.php`:

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'status_label' => $this->status_label, // Uses accessor!
            'priority' => $this->priority,
            'deadline' => $this->deadline->format('Y-m-d H:i'),
            'deadline_status' => $this->deadline_status, // Uses accessor!
            'assigned_to' => [
                'id' => $this->assignee?->id,
                'name' => $this->assignee?->name,
                'email' => $this->assignee?->email,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
```

**Key Points**:
- `$this->status_label` — calls the accessor we created!
- `$this->assignee?->name` — null-safe operator (avoid errors if unassigned)
- Formatting dates consistently
- Including related data (assignee details)

**Example JSON Output**:
```json
{
    "id": 1,
    "title": "Design database schema",
    "description": "Create tables for projects and tasks",
    "status": "in_progress",
    "status_label": "In Progress",
    "priority": "high",
    "deadline": "2026-05-08 16:00",
    "deadline_status": "urgent",
    "assigned_to": {
        "id": 2,
        "name": "Mariam",
        "email": "mariam@example.com"
    },
    "created_at": "2026-05-04 10:00:00",
    "updated_at": "2026-05-06 14:30:00"
}
```

---

#### 3. **Create API Controller**

```bash
php artisan make:controller Api/TaskApiController
```

---

#### 4. **Implement API Endpoint**

Open `app/Http/Controllers/Api/TaskApiController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Project;

class TaskApiController extends Controller
{
    /**
     * Get all tasks for a project (US13)
     * 
     * GET /api/projects/{project}/tasks
     */
    public function index(Project $project)
    {
        // Authorization: user must be able to view the project
        $this->authorize('view', $project);

        // Load tasks with relationships
        $tasks = $project->tasks()
            ->with('assignee')
            ->get();

        // Return as resource collection
        return TaskResource::collection($tasks);
    }
}
```

**How it works**:
1. `TaskResource::collection($tasks)` transforms each task
2. Each task uses TaskResource.toArray()
3. Returns JSON array

---

#### 5. **Register API Routes**

Open `routes/api.php`:

```php
<?php

use App\Http\Controllers\Api\TaskApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/projects/{project}/tasks', [TaskApiController::class, 'index'])
        ->name('api.tasks.index');
});
```

**Key Points**:
- `auth:sanctum` — API authentication using Sanctum tokens
- Routes in `api.php` automatically prefixed with `/api`
- Named routes for clarity

---

#### 6. **Test the Endpoint**

**Browser Test**:
```
GET http://localhost:8000/api/projects/1/tasks
```

**Browser shows JSON if you're authenticated**

**Curl Test**:
```bash
curl -X GET "http://localhost:8000/api/projects/1/tasks" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {token}"
```

**Postman Test**:
- Method: GET
- URL: `http://localhost:8000/api/projects/1/tasks`
- Headers: `Accept: application/json`
- Auth: Bearer {token}

---

#### 7. **Alternative: Show Single Task API** (Bonus)

```php
public function show(Project $project, Task $task)
{
    $this->authorize('view', $task);

    $task->load('assignee');

    return new TaskResource($task);
}
```

Add to routes:
```php
Route::get('/projects/{project}/tasks/{task}', [TaskApiController::class, 'show'])
    ->name('api.tasks.show');
```

---

### What You'll Learn

📚 **Concepts**:
- **Resource Classes** — transformation layer for API responses
- **JSON Formatting** — consistent structure and types
- **Accessor Usage in APIs** — resources leverage model accessors
- **API Authentication** — Sanctum tokens
- **Eloquent in API** — eager loading, relationships
- **RESTful API Design** — consistent endpoints and naming
- **Null-Safe Operator** (?→) — handling optional relationships

📖 **Resources**:
- [Laravel API Resources](https://laravel.com/docs/eloquent-resources)
- [Sanctum Authentication](https://laravel.com/docs/sanctum)
- [RESTful API Design](https://restfulapi.net/)

---

### Acceptance Criteria

- ✅ TaskResource created with proper field mapping
- ✅ Accessors (status_label, deadline_status) used in resource
- ✅ API endpoint `/api/projects/{project}/tasks` working
- ✅ Authorization checked (user must be project member)
- ✅ JSON response includes all relevant fields
- ✅ Dates formatted consistently
- ✅ Assignee data included with null-safety
- ✅ Routes registered in `routes/api.php`
- ✅ Can test in browser/Postman
- ✅ Git commits:
  - `[API] Create TaskResource for JSON formatting`
  - `[API] Implement GET /api/projects/{project}/tasks endpoint`

---

### 🔗 **Deliverables**
- `app/Http/Resources/TaskResource.php`
- `app/Http/Controllers/Api/TaskApiController.php`
- Updated `routes/api.php`
- GitHub commits

---

---

# 🎯 DAY 5 - FINAL TOUCHES & DEBUGGING

## MARIAM & ZINEB - Task 5.1: Project Archive/Restore + Cleanup

**Jira ID**: `DEVTRACK-11`  
**Title**: `[FEATURE] Complete Project Archive/Restore & Soft Deletes (US5, US6, Bonus)`  
**Assignee**: Mariam & Zineb (Pair Programming)  
**Type**: Task  
**Story Points**: 8  
**Priority**: High  
**Timeline**: Friday Morning (3 hours)

### Description

You'll finalize the **archive/restore** feature using Laravel's **Soft Deletes** and add views for managing archived projects.

---

### Detailed Instructions

#### 1. **Add SoftDeletes to Project Migration**

First, create a new migration:

```bash
php artisan make:migration add_soft_deletes_to_projects --table=projects
```

In the migration file:

```php
public function up(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->softDeletes();
    });
}

public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
```

Run migration:
```bash
php artisan migrate
```

---

#### 2. **Add SoftDeletes Trait to Project Model**

In `app/Models/Project.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'created_by',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];
    
    // ... relationships and methods
}
```

**What happens now**:
- `$project->delete()` → marks `deleted_at` but keeps data
- `Project::all()` → only returns non-archived (automatic)
- `Project::withTrashed()` → includes archived
- `Project::onlyTrashed()` → only archived
- `$project->restore()` → unmarks `deleted_at`

---

#### 3. **Update ProjectController.index()**

Modify to show only active projects:

```php
public function index()
{
    $projects = auth()->user()
        ->projects()
        ->with('tasks', 'members')
        ->withoutTrashed()  // Exclude soft-deleted
        ->get();

    $ledProjects = Project::where('created_by', auth()->id())
        ->withoutTrashed()
        ->with('tasks', 'members')
        ->get();

    $projects = $projects->merge($ledProjects)->unique('id');

    return view('projects.index', ['projects' => $projects]);
}
```

---

#### 4. **Add showArchives() Method**

```php
public function showArchives()
{
    // Only show archived projects the user leads
    $archivedProjects = Project::where('created_by', auth()->id())
        ->onlyTrashed()
        ->with('tasks', 'members')
        ->get();

    return view('projects.archives', ['projects' => $archivedProjects]);
}
```

---

#### 5. **Add restore() Method**

```php
public function restore(int $id)
{
    $project = Project::withTrashed()->findOrFail($id);
    
    $this->authorize('restore', $project);

    $project->restore();

    return redirect()
        ->route('projects.index')
        ->with('success', 'Project restored');
}
```

---

#### 6. **Add forceDelete() Method** (Bonus)

```php
public function forceDelete(int $id)
{
    $project = Project::withTrashed()->findOrFail($id);
    
    $this->authorize('forceDelete', $project);

    // Permanently delete
    $project->forceDelete();

    return redirect()
        ->route('projects.archives')
        ->with('success', 'Project permanently deleted');
}
```

---

#### 7. **Create Archives View**

Create `resources/views/projects/archives.blade.php`:

```blade
<h1>Archived Projects</h1>

@if ($projects->isEmpty())
    <p>No archived projects</p>
@else
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Archived Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($projects as $project)
                <tr>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->deleted_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <form action="{{ route('projects.restore', $project) }}" method="POST">
                            @csrf
                            <button type="submit">Restore</button>
                        </form>
                        
                        <form action="{{ route('projects.forceDelete', $project) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger">Delete Permanently</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
```

---

#### 8. **Register Routes**

In `routes/web.php`:

```php
Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);
    
    Route::get('/projects-archives', [ProjectController::class, 'showArchives'])
        ->name('projects.archives');
    
    Route::post('/projects/{project}/restore', [ProjectController::class, 'restore'])
        ->name('projects.restore');
    
    Route::delete('/projects/{project}/force-delete', [ProjectController::class, 'forceDelete'])
        ->name('projects.forceDelete');
});
```

---

### What You'll Learn

📚 **Concepts**:
- **Soft Deletes** (logical delete, not physical)
- **Migrations** for adding columns after initial creation
- **Query Scopes** (withoutTrashed, onlyTrashed, withTrashed)
- **Restoring Data** from soft-deleted records
- **Permanent Deletion** (forceDelete)
- **Timestamp Handling** (deleted_at)

---

### Acceptance Criteria

- ✅ SoftDeletes migration created and applied
- ✅ Project model uses SoftDeletes trait
- ✅ index() excludes archived by default
- ✅ showArchives() shows only archived
- ✅ restore() method works
- ✅ forceDelete() method works
- ✅ Archives view created
- ✅ Routes registered
- ✅ Git commits:
  - `[FEATURE] Add soft deletes to projects`
  - `[FEATURE] Implement archive and restore functionality`

---

---

## MARIAM & ZINEB - Task 5.2: Testing, Debugging & Final Polish

**Jira ID**: `DEVTRACK-12`  
**Title**: `[TESTING] Complete Testing with Debugbar & Telescope + Final Cleanup`  
**Assignee**: Mariam & Zineb  
**Type**: Task  
**Story Points**: 13  
**Priority**: High  
**Timeline**: Friday (Full day)

### Description

You'll run comprehensive tests using **Debugbar** and **Telescope** to verify functionality, catch N+1 queries, debug authorization, and prepare for the debugging session.

---

### Detailed Instructions

#### 1. **Enable Debugbar & Telescope**

```bash
# Debugbar already installed, enable if needed
php artisan config:cache

# Telescope 
php artisan telescope:install
php artisan migrate
```

Both tools show in bottom-right corner during development.

---

#### 2. **Test Each Feature**

**Feature 1: Authentication**
- Register new user → check Telescope
- Login → verify session
- Logout → verify session cleared

**Feature 2: Create Project**
- Click "Create Project"
- Fill form
- Check Debugbar:
  - How many queries executed?
  - Any N+1 issues?
- Verify success message

**Feature 3: Add Members**
- Go to project members
- Add member by email
- Check if user was added to pivot table
- Try adding already-added member (should show warning)

**Feature 4: Create Task**
- Create task in project
- Try to create as developer (should fail at authorization)
- Try to view task as non-member (should fail)

**Feature 5: Update Task Status**
- Login as developer
- Try to view task edit page (should 403)
- Try to update status only (should work)
- Check in Debugbar: what policy method was called?

**Feature 6: Archive Project**
- Archive a project
- Verify it disappears from dashboard
- Go to Archives
- Restore it
- Verify it reappears

**Feature 7: API Endpoint**
- Open `/api/projects/1/tasks` in browser
- Verify JSON response
- Check `status_label` uses accessor
- Try accessing non-member project (should 403)

---

#### 3. **N+1 Query Detection**

In Debugbar, check "Queries" tab:

**Bad (N+1)**:
```
Query 1: SELECT * FROM projects
Query 2: SELECT * FROM tasks WHERE project_id = 1
Query 3: SELECT * FROM tasks WHERE project_id = 2
Query 4: SELECT * FROM tasks WHERE project_id = 3
...
```

**Good (Eager Loading)**:
```
Query 1: SELECT * FROM projects
Query 2: SELECT * FROM tasks WHERE project_id IN (1, 2, 3)
```

**Fix**: Use `with('tasks')` in controller

---

#### 4. **Debug Authorization**

**Scenario 1: Developer tries to edit project**

1. Login as developer
2. Go to `/projects/1/edit`
3. Open Telescope → "Requests" tab
4. Check request details:
   - Status: 403
   - Error: "This action is unauthorized"
5. Check "Gates & Policies" section
   - Shows: Policy `ProjectPolicy@update` returned `false`
   - Why: Developer is not the lead

**Scenario 2: Developer updates task status**

1. Go to task detail
2. Update status
3. In Telescope:
   - Status: 200
   - Gates & Policies: `TaskPolicy@updateStatus` returned `true`
   - Why: Developer is assigned to task

---

#### 5. **Browser Testing Checklist**

- ✅ All links work
- ✅ Forms submit without errors
- ✅ Error messages display for validation failures
- ✅ Flash messages appear on success
- ✅ @can directives hide/show buttons correctly
- ✅ Layouts are consistent
- ✅ No 404 errors
- ✅ No 500 errors
- ✅ Responsive design (optional but nice)

---

#### 6. **Code Cleanup**

Before final commit:

```bash
# Run code standards checker (optional)
# Check for any `dd()`, `var_dump()` debug statements
grep -r "dd\|var_dump" app/

# Clean up unused imports
# Check all migrations ran
php artisan migrate:status

# Check routes
php artisan route:list
```

---

#### 7. **Documentation**

Update README.md:

```markdown
# DevTrack — Project Management for Development Teams

## Installation

1. Clone repository
2. `composer install`
3. `cp .env.example .env`
4. `php artisan key:generate`
5. Configure database in .env
6. `php artisan migrate`
7. `npm install && npm run build` (if using frontend build)
8. `php artisan serve`

## Features

- ✅ User authentication (Breeze)
- ✅ Project CRUD with soft deletes
- ✅ Task management with status tracking
- ✅ Role-based authorization (Lead/Developer)
- ✅ Team member management
- ✅ REST API endpoint for tasks
- ✅ Debug tools (Telescope, Debugbar)

## Architecture

### Models
- **User**: Team members
- **Project**: Projects with teams
- **Task**: Tasks within projects
- **project_user** (pivot): Many-to-many with role

### Policies
- **ProjectPolicy**: Authorization for projects (view, create, update, delete, restore, forceDelete)
- **TaskPolicy**: Authorization for tasks (view, create, update, updateStatus, delete)

### Key Concepts
- **Soft Deletes**: Projects marked deleted but not removed
- **Accessors**: `status_label`, `deadline_status` transform data
- **Eager Loading**: `with()` prevents N+1 queries
- **Form Requests**: Centralized validation
- **API Resources**: JSON formatting for API responses

## Testing

### With Debugbar
1. Open app in browser
2. Check bottom-right corner
3. Click icon to view queries, config, exceptions

### With Telescope
1. Open `localhost:8000/telescope`
2. View requests, queries, exceptions, gates
3. Use for debugging authorization and N+1 queries

## Team

- **Mariam**: Project architecture, project management, authorization policies
- **Zineb**: Database design, task management, API endpoints
```

---

### What You'll Learn

📚 **Concepts**:
- **Debugging Tools** (Debugbar, Telescope)
- **Query Analysis** (detecting N+1)
- **Authorization Debugging** (policy flow)
- **Testing Methodology** (manual browser testing)
- **Code Quality** (cleanup, consistency)
- **Documentation** (README, comments)

---

### Acceptance Criteria

- ✅ All features tested manually
- ✅ No N+1 queries in critical paths
- ✅ Authorization working (403 for unauthorized)
- ✅ API endpoint returns correct JSON
- ✅ No debug statements (dd, var_dump)
- ✅ All routes working
- ✅ Responsive error handling
- ✅ README.md complete and detailed
- ✅ Git commits:
  - `[TESTING] Test all features with Debugbar and Telescope`
  - `[CLEANUP] Remove debug statements and finalize code`
  - `[DOCS] Update README with complete documentation`

---

### 🔗 **Deliverables**
- Fully tested application
- Updated README.md
- Clean codebase
- GitHub commits

---

---

# 📊 Summary & Timeline

## Task Distribution

| Day | Mariam | Zineb |
|-----|-------|-------|
| **Monday** | 01: Database + MCD/MLD | 02: Auth Customization + Models |
| **Tuesday AM** | 03: ProjectPolicy | 04: TaskPolicy |
| **Tuesday PM** | 05: ProjectController | 06: TaskController |
| **Wednesday** | 07: Project Form Requests | 08: Task Form Requests + Accessors |
| **Thursday** | 09: Members Management | 10: API Endpoint + TaskResource |
| **Friday** | 11 & 12: Archive/Restore + Testing (Pair) |

## Key Learnings by Task

| Task | Focus | Concepts |
|------|-------|----------|
| 01 | Data Modeling | MCD, MLD, Relationships |
| 02 | Database | Migrations, Models, Relationships |
| 03 | Authorization (Projects) | Policies, RBAC |
| 04 | Authorization (Tasks) | Custom policy methods |
| 05 | CRUD (Projects) | RESTful routing, controllers |
| 06 | CRUD (Tasks) | Nested resources, custom methods |
| 07 | Validation (Projects) | Form Requests, rules |
| 08 | Validation (Tasks) | Form Requests, Accessors |
| 09 | Team Management | Many-to-many operations |
| 10 | API | Resources, JSON formatting |
| 11 | Soft Deletes | Archive/Restore pattern |
| 12 | Testing & Debugging | Debugbar, Telescope |

---

## Daily Commits Target

- **Monday**: 2-3 commits (Setup, Database)
- **Tuesday**: 4 commits (Policies, Controllers)
- **Wednesday**: 2 commits (Form Requests)
- **Thursday**: 2 commits (Features, API)
- **Friday**: 3+ commits (Archive, Testing)

**Total**: 15+ commits (exceeds 20 target with pair programming)

---

# 🚀 Getting Started Monday

1. **Review this document** (30 min)
2. **Create GitHub repo** and share link
3. **Create Jira board** and share with team
4. **Start Task 1.1** (MCD/MLD design)
5. **Push first commit**: `[SETUP] Initial project structure`

---

**Good luck! You've got this.** 🎉

Remember:
- Ask questions in Jira comments
- Review each other's PRs before merging
- Use Debugbar/Telescope to understand the code
- Commit frequently with clear messages
- Help each other when stuck

---

*End of Task Breakdown*