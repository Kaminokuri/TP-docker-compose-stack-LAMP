<div align="center">

# TP Docker Compose — Stack LAMP (Rocky Linux 10)

Déploiement d’une stack **LAMP** (Linux + Apache + MySQL + PHP) avec **Docker Compose** sur **Rocky Linux 10**.

- **Apache** : image officielle `httpd:2.4`
- **MySQL** : image officielle `mysql:8.0`
- **PHP-FPM** : build via `php/Dockerfile` (PHP 8.3 + extension `mysqli`)
- Code applicatif dans `src/`, monté dans le conteneur
- Accès HTTP via le port **8080**

</div>

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

✅ `localhost` dans le navigateur = **ta machine hôte**, pas la VM.

1. Récupérer l’IP de la VM :

```bash
ip -4 a
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

👉 Dans `src/index.php`, la connexion doit pointer vers **host = `db`**.

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

⚠️ Reset complet (supprime les données MySQL) :

```bash
docker compose down -v
docker compose up -d --build
```

---

## Problèmes rencontrés (et leçon à retenir)

* **403 Forbidden** sur `/` :

  * Souvent dû à l’absence d’index servable (`index.html`) ou à `index.php` non déclaré en index.
  * Solution : ajouter `DirectoryIndex index.php index.html` dans `apache/my-vhost.conf`.

* **Apache ne démarre pas** avec `Invalid command 'cat'` :

  * Cause : une commande shell (`cat > fichier <<EOF`) a été copiée *dans* un fichier de config.
  * Rappel : un fichier `.yml` / `Dockerfile` / `.conf` ne doit contenir que son format, **pas** les commandes shell de création.

* **SELinux (Rocky)** :

  * Les volumes peuvent nécessiter `:Z` (déjà intégré) pour éviter des erreurs de permissions.

---

## Auteur

* GitHub : Kaminokuri
* OS : Rocky Linux 10
* Projet : TP Docker Compose (Stack LAMP)
