# Anti Passoire

Une sorte de mémo dans lequel je pourrai coller tout ce que j'aimerais que mon cerveau retienne ...


choses à faire :
- modifier le contenu de la migration Version20240216230000_insert_admin.php
   en y collant le username et le hash du password désiré.

  pour avoir le hash du password :
  ``` bash
  php bin/console security:hash-password
  ```
- .env :
    - APP_DEBUG=0
    - APP_ENV=prod
    - dans les commandes à effectuer :
      ``` bash
      composer install
      php bin/console doctrine:database:create
      php bin/console doctrine:migrations:migrate
      yarn install
      yarn run build