<div align="center">

# TP Docker Compose — Stack LAMP (Rocky Linux 10)

Déploiement d’une stack **LAMP** (Linux + Apache + MySQL + PHP) avec **Docker Compose** sur **Rocky Linux 10**.

<!-- Badges (style "for-the-badge") — 2 lignes -->


<p>
  <img src="https://img.shields.io/badge/DOCKER-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker" />
  <img src="https://img.shields.io/badge/DOCKER%20COMPOSE-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker Compose" />
  <img src="https://img.shields.io/badge/LINUX-FCC624?style=for-the-badge&logo=linux&logoColor=black" alt="Linux" />
</p>

<p>
  <img src="https://img.shields.io/badge/APACHE%20HTTPD-D22128?style=for-the-badge&logo=apache&logoColor=white" alt="Apache httpd" />
  <img src="https://img.shields.io/badge/MYSQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/PHP%20FPM-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP-FPM" />
  <img src="https://img.shields.io/badge/ROCKY%20LINUX%2010-10B981?style=for-the-badge&logo=linux&logoColor=white" alt="Rocky Linux 10" />
</p>

</div>

---

- **Apache** : image officielle `httpd:2.4`
- **MySQL** : image officielle `mysql:8.0`
- **PHP-FPM** : build via `php/Dockerfile` (PHP 8.3 + extension `mysqli`)
- Code applicatif dans `src/`, monté dans le conteneur
- Accès HTTP via le port **8080**

---

## Dépôt
- GitHub : https://github.com/Kaminokuri/TP-Docker-compose.git

---

## Pré-requis

Sur Rocky Linux 10 (ou toute distro Linux) :
- Docker Engine
- Docker Compose (plugin)
- Git (optionnel, si tu veux cloner/push)

Vérifier :
```bash
docker --version
docker compose version
git --version
````

---

## Arborescence

```
stack_lamp/
├── docker-compose.yml
├── php/
│   └── Dockerfile
├── apache/
│   └── my-vhost.conf
└── src/
    └── index.php
```

* `php/Dockerfile` : construit l’image PHP-FPM + `mysqli`
* `apache/my-vhost.conf` : VirtualHost Apache + proxy des `.php` vers PHP-FPM
* `src/` : contenu web (PHP/HTML)

---

## Démarrage rapide

Depuis la racine du projet :

```bash
docker compose up -d --build
docker compose ps
```

Tester depuis la VM :

```bash
curl -I http://localhost:8080/
```

---

## Accès depuis un navigateur (très important)

1. Récupérer l’IP de la VM :

```bash
ip a
```

2. Dans le navigateur :

```
http://<IP_DE_LA_VM>:8080
```

Exemple :

```
http://192.168.70.128:8080
```

### Firewall Rocky (si accès externe bloqué)

```bash
sudo firewall-cmd --add-port=8080/tcp --permanent
sudo firewall-cmd --reload
```

---

## Configuration MySQL (par défaut)

Les identifiants sont définis dans `docker-compose.yml` :

* Database : `cesi`
* User : `user`
* Password : `user`
* Host : `db` (nom du service docker-compose)
* Root password : `root`

Dans `src/index.php`, la connexion doit pointer vers **host = `db`**.

---

## Logs & debug

Voir les logs :

```bash
docker compose logs --tail=200
docker compose logs --tail=100 apache
docker compose logs --tail=100 php
docker compose logs --tail=100 db
```

Vérifier l’écoute du port :

```bash
ss -lntp | grep ':8080' || echo "8080 pas écouté"
```

---

## Commandes utiles

Valider la config YAML :

```bash
docker compose config
```

Redémarrer Apache :

```bash
docker compose restart apache
```

Arrêter la stack :

```bash
docker compose down
```

Reset complet (supprime les données MySQL) :

```bash
docker compose down -v
docker compose up -d --build
```

---

## Problèmes rencontrés (et leçons à retenir)

* **403 Forbidden** sur `/` :

  * Souvent dû à l’absence d’index servable (`index.html`) ou à `index.php` non déclaré en index.
  * Solution : ajouter `DirectoryIndex index.php index.html` dans `apache/my-vhost.conf`.

* **SELinux (Rocky)** :

  * Les volumes peuvent nécessiter `:Z` (déjà intégré) pour éviter des erreurs de permissions.

---

## Auteur

* GitHub : Kaminokuri
* OS : Rocky Linux 10
* Projet : TP Docker Compose (Stack LAMP)
