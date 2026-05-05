# DevTrack Laravel — Outil de Gestion de Projets et Tâches

> Un outil interne simple de gestion de projets et tâches pour équipes de développement, inspiré de Jira mais sans la complexité.

---

## 📋 Vue d'ensemble

**DevTrack** est une application web construite avec **Laravel** permettant aux Team Leads de gérer des projets et des tâches pour leurs équipes de développement.

### Stack Technique
- **Backend**: PHP (Laravel)
- **Frontend**: HTML5, Blade Templates
- **Base de données**: MySQL
- **Versionning**: Git
- **Format**: JSON (API)
- **Méthodologie**: Scrum

### Formation
- **Programme**: Développeur web et web mobile (DWWM/Backend)
- **Année**: 2023
- **Compétences Transversales**: Référentiels

---

## 🎯 Contexte du Projet

Une **startup installée à Technopark Agadir** a récemment recruté trois développeurs juniors. Le Team Lead gère actuellement le suivi des tâches via :
- WhatsApp
- Excel
- Notes éparpillées

### Problème
- ❌ Pas de visibilité centralisée sur l'avancement
- ❌ Les développeurs ne savent pas clairement quelles tâches leur sont assignées
- ❌ Impossible de suivre l'état du projet en temps réel

### Solution
Construire **DevTrack** : une plateforme interne où :
- Le **Team Lead** crée des projets, invite son équipe et suit l'avancement
- Les **Développeurs** se connectent, voient leurs tâches et mettent à jour leur statut

---

## 👥 Rôles Utilisateurs

| Rôle | Permissions |
|------|-----------|
| **Team Lead** | Créer/modifier/archiver projets · Inviter des développeurs · Créer/modifier/supprimer tâches · Assigner les tâches · Restaurer projets archivés |
| **Developer** | Voir les projets auxquels ils appartiennent · Voir uniquement leurs tâches · Mettre à jour le statut de leurs tâches · Accéder à l'API |

---

## 📖 User Stories

### 1. Authentification 🔐

#### **US1 – Inscription / Connexion / Déconnexion**
**En tant qu'** utilisateur  
**Je veux** créer mon compte, me connecter et me déconnecter  
**Afin que** j'accède à l'application de manière sécurisée

---

### 2. Gestion des Projets 📁

#### **US2 – Dashboard**
**En tant qu'** utilisateur connecté  
**Je veux** voir mes projets (lead ou developer) avec le nombre de tâches totales et terminées par projet  
**Afin que** je dispose d'une vue d'ensemble rapide de mes responsabilités

#### **US3 – Créer un Projet**
**En tant que** lead  
**Je veux** créer un projet avec un titre, une description et une deadline  
**Afin que** je puisse démarrer la gestion d'un nouveau projet

#### **US4 – Modifier un Projet**
**En tant que** lead  
**Je veux** modifier le titre, la description et la deadline de mon projet  
**Afin que** les informations restent à jour

#### **US5 – Archiver un Projet**
**En tant que** lead  
**Je veux** archiver un projet. Il disparaît du dashboard principal mais reste accessible dans la section "Archives"  
**Afin que** je garde un historique sans encombrer ma vue active

#### **US6 – Restaurer un Projet Archivé**
**En tant que** lead  
**Je veux** restaurer un projet archivé pour le ramener dans mon dashboard actif  
**Afin que** je puisse continuer à travailler dessus

#### **US7 – Ajouter / Retirer un Membre**
**En tant que** lead  
**Je veux** ajouter un développeur à mon projet en entrant son email, et le retirer si nécessaire  
**Afin que** je contrôle qui a accès aux tâches du projet

---

### 3. Gestion des Tâches

#### **US8 – Liste des Tâches d'un Projet**
**En tant que** lead ou developer membre du projet  
**Je veux** voir toutes les tâches du projet avec :
- Titre
- Statut (À faire / En cours / Terminé)
- Developer assigné
- Indicateur d'urgence basé sur la deadline

**Afin que** je visualise l'avancement global

#### **US9 – Créer une Tâche**
**En tant que** lead  
**Je veux** créer une tâche avec :
- Titre
- Description
- Deadline
- Priorité (low/medium/high)
- Assignation à un developer du projet

**Afin que** je découpe le travail et l'assigne clairement

#### **US10 – Modifier une Tâche**
**En tant que** lead  
**Je veux** modifier n'importe quelle tâche de mon projet  
**Afin que** je corrige ou mette à jour les détails

#### **US11 – Changer le Statut d'une Tâche**
**En tant que** developer assigné à une tâche  
**Je veux** changer le statut de cette tâche : `todo` → `in_progress` → `done`  
**Je ne peux pas** modifier autre chose  
**Afin que** je mette à jour l'avancement de mon travail

#### **US12 – Supprimer une Tâche**
**En tant que** lead  
**Je veux** supprimer une tâche de mon projet  
**Afin que** je nettoie les tâches devenues obsolètes

---

### 4. API 🔌

#### **US13 – Endpoint API Tâches**
**En tant que** développeur  
**Je veux** accéder à `GET /api/projects/{project}/tasks` et recevoir les tâches en JSON formaté  
**Avec** une `TaskResource` incluant le statut formaté via l'accessor  
**Afin que** je puisse intégrer DevTrack dans d'autres outils

---

## 🎁 Bonus

1. **Suppression définitive** : depuis la page Archives, le lead peut supprimer définitivement un projet avec `forceDelete()`

2. **Mutator** : ajouter un mutator sur `title` dans le model `Project` qui stocke automatiquement le titre en `ucfirst`

3. **Local Scope** : ajouter un scope `urgent()` sur `Task` qui filtre les tâches :
   - Deadline dans moins de 48h
   - Statut différent de `done`

---

## ⚠️ Contraintes Techniques Obligatoires

### Routing

- ✅ **Routes nommées** : utiliser `->name()` sur toutes les routes
- ✅ **Routes web groupées** sous middleware `auth`
- ✅ **Route API** dans `routes/api.php`

### Eloquent & Base de Données

- ✅ **Many-to-many** `users ↔ projects` avec colonne `role` dans la table pivot
- ✅ **`$fillable` défini** dans chaque modèle
- ✅ **Trait `SoftDeletes`** utilisé sur le model `Project`
- ✅ **`with()` obligatoire** — zéro N+1 vérifié avec Debugbar

### Autorisation

- ✅ **`ProjectPolicy` et `TaskPolicy`** créées avec `php artisan make:policy`
- ✅ **`$this->authorize()`** dans tous les controllers concernés
- ✅ **`@can`** dans les vues pour masquer les actions selon le rôle
- ✅ **Zéro `abort(403)` manuel**

### Blade Templates

- ✅ **Layout principal** : `layouts/app.blade.php`
- ✅ **`@auth / @guest`** pour l'affichage conditionnel de la navigation
- ✅ **`@can`** pour les boutons d'action selon le rôle

### Sécurité Minimale

- ✅ **Validation via Form Request classes** sur tous les formulaires
- ✅ **`@csrf`** sur tous les formulaires
- ✅ **Redirection automatique** vers `/login` si non connecté

---

## 📅 Modalités Pédagogiques

| Aspect | Détails |
|--------|---------|
| **Mode** | 👥 Binôme |
| **Durée** | ⏱️ 5 jours |
| **Lancement** | 🚀 Lundi 04/05/2026 – 10:00 |
| **Deadline** | 🏁 Vendredi 08/05/2026 – 16:00 |

---

## 🧪 Modalités d'Évaluation

### Session de Debugging — 30 minutes par binôme

#### **Phase 1 — Telescope** (10 min)
Trois requêtes déclenchées sur le projet. Chaque membre explique une requête :
- Payload reçu
- Queries SQL exécutées
- Réponse retournée
- Exceptions éventuelles

#### **Phase 2 — Bug Hunt** (10 min)
3 bugs introduits dans le code. **Trouvés avec Debugbar et Telescope uniquement** — pas en lisant le code directement.
- Chaque membre trouve et corrige au moins un bug

#### **Phase 3 — Question Individuelle** (10 min)

**Membre 1:**  
_"Un developer tente d'accéder à `GET /projects/{id}/edit` d'un projet dont il est membre mais pas lead. Montre dans Telescope ce qui se passe et explique comment la `ProjectPolicy` bloque cette action."_

**Membre 2:**  
_"Ouvre `/api/projects/{id}/tasks` dans le navigateur. Explique comment l'accessor `status_label` transforme la valeur brute avant qu'elle apparaisse dans la `TaskResource`. Montre le code dans le model."_

---

## 📦 Livrables

### 1. Repository GitHub

- ✅ **Minimum 20 commits** répartis entre les 2 membres
- ✅ **Feature branches** avec PR avant merge sur `main`
- ✅ **Au moins 1 review par PR** visible sur GitHub
- ✅ **Zéro commit direct** sur `main`

### 2. Jira Board

- ✅ Board partagé avec `abderahmane.merradou@gmail.com` avant lundi 12h
- ✅ **Sprint backlog complet** dès lundi après-midi

### 3. MCD & MLD

- ✅ **Rendu lundi avant 14h00**
- ✅ **MCD** : entités sans types ni FK
- ✅ **MLD** : tables avec types, PK, FK — table pivot

### 4. Présentation

**Format**: Slides obligatoires (Canva, PowerPoint ou Google Slides)

**Structure Obligatoire** (10 slides minimum):
1. Titre & Équipe
2. Contexte & Problème
3. MCD
4. MLD
5. Architecture & Méthodologie
6. Les deux rôles & Policies
7. Concepts nouveaux (soft deletes, accessors, Form Requests)
8. Démo live
9. Difficultés & Solutions
10. Conclusion

**Règles Slides**:
- 📝 Maximum **30 mots** par slide
- 🎨 **Minimum 1 visuel** par slide
- 🔤 Police **minimum 24px**
- 📌 **Maximum 3 points** par slide
- 🔢 **Numérotation visible**
- 📊 **Slides MCD et MLD obligatoires**

### 5. README.md

Documentation complète du projet (installation, usage, architecture)

---

## 🎯 Critères de Performance

### Architecture Laravel (35%)

- ✅ **Policies** (`ProjectPolicy` + `TaskPolicy`) pour toutes les autorisations — zéro `abort(403)` manuel
- ✅ **Form Request classes** pour toutes les validations
- ✅ **Many-to-many** avec colonne `role` dans la table pivot
- ✅ **Soft deletes** sur `Project` avec archive et restauration fonctionnels
- ✅ **Accessors** `status_label` et `deadline_status` utilisés dans vues et API Resource
- ✅ **Zéro N+1** vérifié avec Debugbar

### Fonctionnalités (25%)

- ✅ **CRUD projets complet** avec gestion des membres (lead vs developer)
- ✅ **CRUD tâches** avec assignation et changement de statut selon rôle
- ✅ **Archive / restauration** de projets fonctionnelle
- ✅ **Endpoint API** `/api/projects/{project}/tasks` retourne du JSON propre

### Présentation (20%)

- ✅ **Structure 10 slides** respectée avec MCD et MLD
- ✅ **Explication claire** des concepts nouveaux (Policies, soft deletes, accessors)
- ✅ **Démo live** fonctionnelle
- ✅ **Règles slides** respectées (mots, visuels, numérotation)

### Collaboration & Process (20%)

- ✅ **20 commits** répartis entre les 2 membres
- ✅ **Feature branches** + PR avec reviews avant merge
- ✅ **Jira board** à jour avec historique visible

---

## 🚀 Commandes Laravel Essentielles

```bash
# Créer les policies
php artisan make:policy ProjectPolicy --model=Project
php artisan make:policy TaskPolicy --model=Task

# Créer les Form Requests
php artisan make:request StoreProjectRequest
php artisan make:request UpdateProjectRequest
php artisan make:request StoreTaskRequest
php artisan make:request UpdateTaskRequest

# Créer les Resources
php artisan make:resource TaskResource

# Migrations
php artisan migrate
php artisan migrate:refresh --seed
```

---

## 📚 Ressources Utiles

- [Laravel Policies Documentation](https://laravel.com/docs/policies)
- [Laravel Form Requests](https://laravel.com/docs/requests)
- [Laravel Soft Deletes](https://laravel.com/docs/eloquent#soft-deleting)
- [Laravel Accessors & Mutators](https://laravel.com/docs/eloquent-mutators)
- [Laravel API Resources](https://laravel.com/docs/eloquent-resources)
- [Telescope Debugging Tool](https://laravel.com/docs/telescope)

---

**Version**: 1.0  
**Dernière mise à jour**: 04/05/2026