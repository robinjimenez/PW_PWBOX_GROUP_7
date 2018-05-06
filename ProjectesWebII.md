# PROJECTES WEB II



## Introducció

### Vagrant

*Vagrant* és un **gestor** de màquines virtuals. Permet fer accions relacionades amb màquines virtuals a través del Terminal (instalar-les, gestionar-les…).

*HomeStead* és una **Box** concreta (un paquet) de Vagrant, on hi ha una màquina virtual amb software (Linux, mySQL, PHP...).

La màquina virtual s'instala en un **Provider**, en aquest cas *VirtualBox*.



### Homestead.yaml

Fitxer on podem configurar coses de la màquina virtual. Es poden posar les **carpetes que volem compartir** entre el meu ordinador real i la màquina virtual. També es configuren els sites: són les adreces de virtual host que tenim disponibles.

https://scotch.io/tutorials/getting-started-with-laravel-homestead

https://abbasharoon.me/homestead-yaml-explained-a-z/



### Terminal

Per utilitzar la màquina virtual:

- `cd /Users/jordi/HomeStead`

- `Encendre VM: vagrant up`

- `Estat VM: vagrant status`

- `Accedir VM: vagrant ssh`

- `Sortir VM: exit`

- `Apagar VM: vagrant halt`

  ​


```
ALTRES FUNCIONS TERMINAL
touch <file.html> //Crear un arxiu a la carpeta actual
clear //netejar terminal
cd
cd ..
open
rm <file>//eliminar fitxer
rm -f <file>//forçar eliminar fitxer
mkdir//crear carpeta
rm -rf <carpeta>//eliminar carpeta
vim <fitxer>//obrir amb editor vim
```




### Git

Permet tenir diferents versions d'un programa i compartir un fitxer en grups de treball. Els fitxers passen de **Modified** (modificat respecte últim update), a **Staged** (es penjaran així en el següent commit), a **Commited** (el fitxer s'ha penjat a git fent un commit).

https://www.youtube.com/watch?v=SWYqp7iY_Tc

###### Comandes:

```
PAS REPOSITORIS LOCALS

git init //inicia repositori local de git a la carpeta actual (crea carpeta oculta .git)
git add <file> //afegeix file a Staged Area (al repositori local)
git add . //afegeix tots els files de la carpeta a Staged Area
git rm --cached <file> //Eliminar fitxer de Staged Area
git status //mostra què hi tenim en el Staged Area
git commit //afegeix tot el que hi ha en la Staged Area al Repositori local
	Dins del vim del commit:
	- "i" per inserir text per al missatge de commit
	- esc per a sortir del mode insert
	- ":wq" per a executar el commit
git commit -m 'comentari rapid' //permet fer commit al repositori local sense haver d'entrar en la pantalla del vim

git reflog //mostra tots els commits anteriors
git reset --hard HEAD@{index} //time-machine per tornar a un estat del programa d'un commit anterior
```

```
PAS REPOSITORIS REMOTE (GITHUB...)

git push //push al repositori remote. Es penja a github (repositori remot) el que teniem com a Commited (ha calgut add i commit al repositori local abans) del repositori local.
git pull //actualitzar la carpeta actual i descarregar canvis del repositori remot
git clone https://github.com/jordiam97/exempleGit.git //clone repository de github (url agafada de la web) a la carpeta actual

Indicacions inicials des de la pàgina de GitHub:
1) git remote //mostra què tenim al repositori remot
2) git remote add origin https://github.com/jordiam97/exempleGit.git //per a vincular amb repositori remot creat a github 
3) git push -u origin master //per a penjar al repositori remot la nostra branca master del repositori local
```

```
ALTRES FUNCIONS GIT
git --version //comprovació que està instalat
git config --global user.name 'Jordi' //Per registar nom
git config --global user.email 'j@j.com' //Per registar email
```

```
FUNCIÓ GITIGNORE - Per ignarar determinats fitxers de la carpeta i que no els controli git
touch 

1) touch .gitignore
2) obrir fitxer .gitignore (atom) i escriure el nom del fitxer/carpeta a ignorar (ho podem comprovar amb el git status que no s'afegeix al staged area).
```



###### Branches:

Quan estem treballant en un projecte, si estic fent determinades parts i no vull fer commit al "grup general" (branca master) fins que no estigui acabada la meva feina per no penjar coses a mitges, puc treballar (i fer commits) en una nova branca.

```
git branch nomDeLaNovaBranch //crear nova branca
git checkout nomDeLaBranch //per fer el switch i passar a treballar a una altre branch (master o una secundària). Quan canviem de branch, desapareix allò que era només propi de l'anterior.
git merge nomDeLaBranch //per a unir branch amb la master. Fitxers de cada branch fan merge.
```

La **branca Master** sempre apunta a l'últim commit. 

El **punter** **Head** apunta a la branca actual (pot  ser la master o una altre)

Podem crear-nos una altre branca on fer els canvis futurs. Quan hem acabat els canvis, fem **merge** de la branca actual a la branca Master.

https://gitlab.com/salle-projectes-web/git-basics



### Composer

https://desarrolloweb.com/manuales/tutorial-composer.html#capitulos187

http://weaintplastic.github.io/web-development-field-guide/Development/Frontend_Development/Setting_up_your_project/Setup_Dependency_Managers/Composer/Initialize_Composer_on_a_new_Project.html

https://gitlab.com/salle-projectes-web/composer-basics

És un gestor de dependències PHP, és a dir, permet instalar i actualitzar llibreries , frameworks i altres tipus de fitxers PHP ja creats per altres persones per a utilitzar-los en el nostre projecte. Ens estalvia haver de instalar i actualitzar manualment cada llibreria o framework que utilitzem en el nostre projecte. (Seguint les diapositives de la Salle ho he instalat de manera global: puc accedir a composer des de qualsevol lloc/projecte).

<u>Funcionament</u>: 

1) Tenim un fitxer *composer.json* on escrivim tots els paquets que utilitzarem en el projecte. Cridem a `composer init` per a crear el json (ens va preguntant coses que aniran dins del json: https://goo.gl/BTs2mu). 

2) Un cop escrits tots els paquets (llibreries, etc.) en el camp require del json, haurem d'utilitzar `composer install` i ell sol instalarà tots els paquets (Alternativa: es pot instalar automàticament la llibreria si tenim la comanda de terminal d'aquesta, per exemple: `composer require --dev phpunit/phpunit`.) 

3) Totes les llibreries s'instalaran en una carpeta en el nostre projecte anomenada *vendor.* Per incloure totes les llibreries instalades de cop al nostre projecte només caldrà escriure en el codi PHP `require 'vendor/autoload.php'`. El fitxer composer.lock indica la versió instalada de totes les llibreries.

4) Si afegim més paquets o volem actualitzar els que tenim, fer `composer update`.

*Tots els paquets que podem instalar per composer es troben a <https://packagist.org/>.



------



# PHP

Llenguatge per a gestionar servidors. **S'executa a través del terminal:** 

```
php scriptName.php 
```

El codi va entre tags  `<?php       -codi-         ?>`



## Basics

#### Variables

- Tipus bàsics: **boolean, integer, float, string, array, object, null**
- S'escriuen amb un dollar davant. Per exemple: `$message = "Hello world";`
- Hi ha variables predefinides, les Superglobals `($_SERVER, $_POST, $_GET, …)`
- Constants en majúscules i desfinició per clau-valor: `define('NAME', 'Jordi');`
- En les funcions podem **declarar** el pas dels paràmetres per valor **o per referència**, com en C: `public function changeName(&$name){...}`  

Printar text: `echo "Hello world";`

Printar variables: `var_dump($message);`

Printar arrays: `print_r();`

Per reconéixer variables en un text escrit calen doble cometes. `echo "Vull printar $message";`

Per concatenar text amb variables s'uutilitza un punt: `$dsn = 'mysql:host='. $host;`



#### Arrays

Són sempre en **format clau-valor**.

```php
$array = array (
	"name" => "Jordi",
    "surname" => "Alonso",
    23 => 12,
    "multiArray" => array(
    	"age" => 21
    )
);

var_dump($array["multiArray"]["age"]);//printa valor de l'array
```

Altres funcions de Array: implode, explode, unset, … (http://php.net/manual/en/ref.array.php)



#### Class and Objects

Els objectes són instàncies d'una classe.

Per accedir a mètodes de la classe no s'utilitza el punt. **S'utilitza la fletxa**.

Per crear la classe:

```php
class User {
    private $name;
    
    public function __construct($name) {//Mètode constructor
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }     
}

$jordi = new User("Jordi");
echo $jordi->getName();
```

Els atributs de les classes poden ser *private, protected o public*.



###### Herència

Quan una classe hereda d'una altre, **reb tots els mètodes i atributs public o protected** (no private). Els **pot sobreescriure**.



###### Classes abstractes

Les classes abstractes no es poden instanciar (tenen mètodes normals o abstractes -> no implementats). Només serveixen per a definir subclasses que heretin d'aquesta. Les subclasses hauran d'implementar els mètodes abstractes de la classe abstracta.



###### Interfícies

Són contractes, de manera que les classes que la implementin han de implementar TOTS els mètodes que defineix la interfície.



#### Namespaces

Agrupació de classes que tenen relació entre elles. Permeten tenir classes que es puguin dir igual en el mateix projecte, ja que cadascuna estarà en un namespace diferent. Només 1 namespace per fitxer.

Si els creem en el menú de l'esquerra de fitxers, només cal indicar el namespace a l'inici del tag php:

```php
namespace Connections;
```

Podem incloure altres fitxers al namespace en el codi:

```php
namespace Connections;
include 'Databases.php';
use Connections\Databases as db;

$db = new db();
```



#### Magic Methods

http://php.net/manual/en/language.oop5.magic.php

Mètodes que es criden automàticament en determinats casos:

```php
__construct(), __destruct(), __call(), __callStatic(), __get(), __set(), __isset(), __unset(), __sleep(), __wakeup(), __toString(), __invoke(), __set_state(), __clone(), __debugInfo()
```



## Files

https://www.youtube.com/watch?v=sLLZU38Okgo&index=19&list=PLillGF-Rfqbap2IB6ZS4BBBcYPagAjpjn

Amb PHP podem accedir i fer determinades accions amb els fitxers del nostre ordinador (màquina virtual).

Funcions per a tractar fitxers:

```php
$path = '/dir1/myfile.php';
$file = 'file1.txt';

basename($path);//retorna 
basename($path, '.php');//no retorna el .php
dirname($path);

file_exists($file);//comprovació si fitxer existeix
realpath($file);//retorna path complet
is_writable($file);
is_readable($file);
filesize($file);
mkdir('carpeta');//crea directori
rmdir('carpeta');//elimina directori si està buit

copy('file1.txt', 'file2.txt');//copia fitxer 1 a fitxer 2
rename('file1.txt', 'nouNom.txt');//rename file
unlink('file1.txt');//eliminar fitxer

//https://stackoverflow.com/questions/10897084/file-vs-file-get-contents-which-is-better-php
$file = file(__DIR__ ."/fileName.txt");//write from file to ARRAY. Aqui utilitzant la supervariable de directori
$file = file_get_contents(__DIR__ ."/fileName.txt");//write from file to STRING. Llegeix el contingut del fitxer com a string. Aqui utilitzant la supervariable de directori
file_put_contents($file, 'text escrit');//escriu en el fitxer i reemplaça el que hi havia. combinar amb file_get_contents per no sobreescriure:

$content = file_get_contents($file);
$content .= "New content";
file_put_contents($file, $content);

//Open file for reading
$handle = fopen($file, 'r');//Obtenim fitxer per llegir, el fiquem dins d'una variable handle. Després podrem utilitzar la variable handle per a realitzar accions amb el fixter.
$data = fread($handle, filesize($file));
echo $data;
fclose($handle);

//Open file for writing
$handle = fopen($file, 'w');
$txt = 'Jordi Alonso';
fwrite($handle, $txt);
fclose($handle);
```



## Forms

S'utilitzen les variables superglobal `$_GET o $_POST` per accedir al contingut del formulari. Aquestes variables **són Arrays** que contenen la informació dels camps dels formularis.



###### $_GET: La info del formulari s'envia a través de la URL

*Exemple: Printar nom d'un formulari*:

En la part del HTML, en l'etiqueta del formulari s'indica el mètode PHP a cridar:

```html
<form method="GET" action="get_post.php"><!-- En el method posem GET o POST-->
    <input type="text"name="name"><!-- L'etiqueta name per accedir des del PHP-->
</form>
```

Part PHP:

```php
<?php
    if (isset($_GET['name'])) {//Comprova si el camp està buit o no (set)
        $name = $_GET['name'];
        echo $name;
        /*
        $name = htmlentities($_GET['name']);//Més segur, evita PHP injection
        echo $name;
        */
    }
?>
```



###### $_POST: Enviament segur de les dades (no URL)

*Exemple: Printar nom d'un formulari*:

En la part del HTML, en l'etiqueta del formulari s'indica el mètode PHP a cridar:

```html
<form method="POST" action="get_post.php"><!-- En el methos posem GET o POST-->
    <input type="text" name="name"><!-- L'etiqueta name per accedir des del PHP-->
</form>
```

Part PHP:

```php
<?php
    if (isset($_POST['name'])) {//Comprova si el camp està buit o no (set)
        $name = $_POST['name'];
        echo $name;
        /*
        $name = htmlentities($_GET['name']);//Més segur, evita PHP injection
        echo $name;
        */
    }
?>
```

*També existeix la superglobal $_REQUEST (funciona tant per GET o POST en el HTML).*

https://www.youtube.com/watch?v=cIFUH3Qnd6s&list=PLillGF-Rfqbap2IB6ZS4BBBcYPagAjpjn&index=11



*Utilitzant Slim és una mica diferent la sintaxi!



## Forms with Files

Per a combinar Files amb Formularis (Files que s'envien al servidor a través d'un Formulari): cal ajustar el HTML del formulari i la variable superglobal (array amb la informació) que utilitza PHP (Abans  `$_GET o $_POST` ), ara és `$_FILES`.



###### $_FILES: Enviar files a través d'un formulari

Part HTML:

```html
<form method="post" enctype="multipart/form-data" action="upload.php"><!--és post-->
	<input type="file" name="myFile"><!-- input del tipus file-->
 	<input type="submit" value="Upload">
</form>
```



Cada Array $_FILES del fitxer "myFile" enviat, conté els següents camps:

```
$_FILES["myFile"]["name"] stores the original filename from the client
$_FILES["myFile"]["type"] stores the file’s mime-type
$_FILES["myFile"]["size"] stores the file’s size (in bytes)
$_FILES["myFile"]["tmp_name"] stores the name of the temporary file
$_FILES[“myFile”][“error”] stores any error code resulting from the transfer
```

Més info: https://www.sitepoint.com/file-uploads-with-php/



## Bases de Dades: PDO

https://www.youtube.com/watch?v=kEW6f7Pilc4

https://gist.github.com/bradtraversy/147443539b7e1afafa17e6392f072720

PDO (PHP Data Objects) és una extensió de PHP per a treballar amb Bases de Dades (Alternativa: MySQLi).  

Classes de la extensió: PDO (per la connexió PHP amb BBDD), PDOStatement (representa un prepared statement i el seu resultat), PDOException.



Accedir a MySQL de HomeStead des del terminal:

```
vagrant up
vagrant ssh
mysql -u homestead -p
	[password = secret]

//Altres funcions (ja són funcions MySQL)
show databases;
use <nomDB>;
show tables;
exit;
```



Accedir a MySQL de HomeStead des d'aplicacions externes (de de ordinador local):

![Captura de pantalla 2018-04-21 a las 16.27.28](/Users/jordi/Documents/Treballs laSalle/Projectes Web 2/rec/Captura de pantalla 2018-04-21 a las 16.27.28.png)

*Utilitzant Sequel Pro: https://www.youtube.com/watch?v=dkpIDRX916M*



##### Connexió BBDD des de PHP

1) Definir variables amb dades de la BBDD:

```php
$host = '127.0.0.1';
$user = 'homestead';
$password = 'secret';
$dbname = 'pdoposts';
```

2) Definir DSN i crear instància de pdo per connectar-nos a BBDD amb PDO:

```php
//Set DSN
$dsn = 'mysql:host='. $host. ';dbname=' .$dbname;

//Create a PDO instance (és la Base de dades)
$db = new PDO($dsn, $user, $password);
```



##### Querys

Utilitzant la funció *query* de PDO. Retorna un objecte de la classe PDOStatement.

```php
$rows = $db->query('SELECT * FROM taula1');//Retorna les files

//Recorrem totes les files i printem concatenades 2 columnes

//Opció 1: foreach de rows
foreach ($rows as $row) {
    echo $row['column1']. '<br>'. $row['column2']. '<br>';
}

//Opció 2: utilitzant funció fetch. Fetch indica com la següent row serà retornada, en quin format (com array, com objecte, etc).
while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {//FETCH_ASSOC retorna la row com array
   	echo $row['column1']. '<br>'. $row['column2']. '<br>';
}

while ($row = $rows->fetch(PDO::FETCH_OBJ)) {//FETCH_OBJ retorna la row com a object. Cal modificar echo.
    echo $row->column1 . '<br>';
}
```

Es pot definir el tipus de Fetch que s'utilitza sempre a l'inici (per l'opció 2 de querys):

```php
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

//Ara podem recorrer les rows sense especificar el tipus de fetch
while ($row = $rows->fetch()) {
   	echo $row['column1']. '<br>'. $row['column2']. '<br>';
}
```



##### Prepared Statements

- Guardem querys per executar-les múltiples vegades (com si fossin una funció). Podem passar-lis paràmetres diferents cada cop.
- 2 mètodes principals: prepare i execute.
- Als paràmetres de la query **no li passem les variables directament**. Es fa a través de *bindParam* (evitar SQL Injection).

```php
$name = 'jordi';//Obtenim paràmetres abans del statement
$email = 'whatever@mail.com';

$stmt = $db->prepare("SELECT * FROM taula1 WHERE name = ? AND email = ?");//interrogants representen les variables

$stmt->bindParam(1, $name, PDO::PARAM_STR);//introdueix variables en ordre
$stmt->bindParam(2, $email, PDO::PARAM_STR);

$stmt->execute();//executa statement

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);//Recuperem tots els resultats de cop (fetchAll) com a array
```



##### Filtres

Funcions per a comprovar i filtres formats que rebem de l'usuari: *filter_var* i *filter_input*.

```php
filter_var($email, FILTER_VALIDATE_EMAIL);//retorna email o false
filter_var($email, FILTER_SANITIZE_EMAIL);//elimina caracters incorrectes
```



## Sessions

https://www.youtube.com/watch?v=W4rSS4-LdIE&list=PLillGF-Rfqbap2IB6ZS4BBBcYPagAjpjn&index=16

Manera de mantenir informació **entre** diverses pàgines (diversos .php) d'una mateixa web (una web pot tenir diversos .php).

Informació emmagatzemada en el servidor, no en el navegador del client. S'eliminen quan s'apaga el navegador (s'acaba la sessió).



1) Cal iniciar sessió **en cada pàgina** on utilitzem informació de sessió: 

```php
session_start();
```

2) Guardem la informació en variables de sessió. Accedim a elles amb la variable superglobal *(recordar: les variables superglobal són arrays!).*

```php
if (!isset($_SESSION['name'])) {//Si no està iniciada variable 'name'
    $_SESSION['name'] = 'Jordi';//Guardem la variable de sessió 'name'
}else {
    $_SESSION['name'] = 'Anna';
}
```

3) En una altre pàgina, podem accedir a la variable de sessió:

```php
$name = $_SESSION['name'];
```

4) Per eliminar una variable de sessió o la sessió completa:

```php
unset($_SESSION['name']);
session_destroy();//elimina totes les variables de la sessió
```



*Per a navegar a diferents pàgines d'una mateixa web:

```php
header('Location: page2.php');
```



## Cookies

https://www.youtube.com/watch?v=RzMjwICWKr4&list=PLillGF-Rfqbap2IB6ZS4BBBcYPagAjpjn&index=17

Manera de guardar informació **en el navegador** del client, també per a poder accedir a aquesta des de les diferents pàgines d'una web.

Menys segur que les variables de Sessions. No s'eliminen tot i que l'usuari tanqui el navegador (acabi la sessió).

Les cookies es guarden a la variable superglobal `$_COOKIE` (array de cookies).



1) Crear cookie:

```php
$name = 'Jordi';

setcookie('name', $name, time()+3600);//temps d'expiració en 1 hora (3600 segons)
```

2) Per eliminar cookie posem un temps d'expiració que ja ha passat:

```php
setcookie('name', $name, time()-3600);
```

3) Podem accedir a les cookies (des de la mateixa o des d'una altre pàgina):

```php
$name = $_COOKIE["name"];
```



*Podem emmagatzemar més d'una dada en una cookie utilitzant un Array, que cal serialitzar:

```php
$user = ['name' => 'Jordi', 'surname' => 'Alonso', 'email' => 'j@j.com'];//array amb les dades

$user = serialize($user);//converteix array en format string per poder-ho emmagatzemar com a cookie

setcookie('user', $user, time()+3600);//creem la cookie amb l'array serialitzat

//Per accedir a l'array guardat a la cookie cal deserialitzar l'array
$user = unserialize($_COOKIE['user']);
```



# Slim

https://www.slimframework.com/docs/v3/tutorial/first-app.html

És un framework de PHP. Permet fer funcions i **organitzar el codi** de manera més eficient. Obliga a treballar amb MVC.



## Inici

1) Instalar slim amb composer en la carpeta del nostre projecte:

```
composer init
composer require slim/slim “^3.0”
```



2) Crear fitxer d'inici *index.php* al directori *public* (dins de public posem el index.php que executa el virtual host pel navegador). Plantilla fitxer inici:

```php
<?php
//Importem classes de php per a la comunicació de missatges amb html (estandart)
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//Importar vendor (carpeta amb els recursos instalats per composer -slim-). El path pot ser diferent per mi!
require '../vendor/autoload.php';

//Creem l'objecte app, la base d'un projecte amb slim
$app = new \Slim\App;

//Mètode d'exemple que mostra un missatge quan realitzem una crida per url (get) amb el format "/hello/nom".
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

//Iniciem slim i el projecte en si.
$app->run();
```

Provar exemple anant a `http://tests.dev/hello/jordi`.



3) Afegir fitxer de configuració del nostre projecte. Permet afegir dades per al projecte de forma general.

Crear-lo a una carpeta separada: `/app/settings.php`

Per exemple, settings.php pot ser així (inclou dades de connexions amb BBDD, etc.):

```php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'database' => [
            'dbname' => 'PWbox',
            'user' => 'web2',
            'password' => 'web2',
            'host' => 'localhost',
            'driver' => 'pdo_mysql'
        ]
    ]
];
```

En el index.php cal canviar al fer new de la app de Slim. Ara:

```php
$settings = require_once __DIR__ . '/../app/settings.php';
$app = new \Slim\App($settings);//nova app amb els settings indicats
```

We’ll be able to access any settings we put into that `$settings` array from our application later on.



4) Editar composer.json i posar l'apartat autoload perquè es carreguin correctament les meves pròpies classes a part de les que hi ha a la carpeta vendor. Posem path d'on tenim tots els fitxers amb classes (en aquest cas, projecte pràctica de web, el codi està dins de src).

```php
"autoload": {
    "psr-4": {
    	"PWBox\\": "src/"
    }
 }
```

Executar llavors `composer dumpa`.

Comprovació a `/vendor/composer/autoload_psr4.php`. Ha d'aparéixer `'PWBox\\' => array($baseDir . '/src')` en aquest cas.



5) Afegir dependències (llibreries externes). Es posen en un fitxer a part, a `/app/dependencies.php`. Mirar a la web l'explicació completa: https://www.slimframework.com/docs/v3/tutorial/first-app.html

Cal afegir `require_once __DIR__ . '/../app/dependencies.php';` al index.php.



6) Afegir rutes en un fitxer a `/app/routes.php`. (Després de les dependències!)

Les rutes són totes les url per cada pàgina que tindrà la nostre web.

En el fitxer index.html, hem d'incloure també el fitxer: `require_once __DIR__ . '/../app/routes.php';`

Mirar explicació completa a https://www.slimframework.com/docs/v3/tutorial/first-app.html



*Finalment, el fitxer index.php ha de quedar d'aquesta manera (només crea app Slim i importa fitxers):

```php
<?php

require '../vendor/autoload.php';

$settings = require_once __DIR__ . '/../app/settings.php';

$app = new \Slim\App($settings);

require_once __DIR__ . '/../app/dependencies.php';
require_once __DIR__ . '/../app/routes.php';

$app->run();
```



## Vistes: Twig

Template Engine: Eina o llibreria que **permet separar el codi** **php de les vistes html** (templates). Les templates només contenen html. Twig és un engine template.

Per exemple, per a printar text en un .php hauriem de fer `<p><?php echo $name; ?></p>`. Si utilitzem Twig, només posem `<p> {{ name }} <p>`.

Per integrar Twig a Slim:

1) Instalar Twig amb Composer: `composer require slim/twig-view`

2) Afegim Twig com a dependència de Slim en el fitxer dependencies.php

Els fitxers html (templates) són amb extensió .twig ara.

En el fitxer de routes.php cridarem a un controlador de la ruta que retornarà la vista (template .twig) que toqui.

Podem carregar assets (css, imatges, etc. que tindrem en una carpeta a /public/assets ) en el template .twig utilitzant la funció base_url de twig en el head de la pàgina:

 `<link rel="stylesheet" href="{{ base_url() }}/assets/css/style.min.css">`

Podem fer herència de templates: A partir d'una template base.twig, podem crear altres que heredin de la primera (compartiran elements bàsics).

Sintaxi de Twig: https://twig.symfony.com/doc/2.x/templates.html#template-inheritance

https://twig.symfony.com/doc/2.x/api.html



## Controladors slim

Encarregat de controlar les accions de les rutes. No executen lògica de la aplicació (això ho fa el Model).

#### Rutes

Dins del fitxer routes.php.

Podem fer rutes que s'encarreguen de les peticions HTTP get, post, etc. S'utilitzen els mètodes get o post a partir de la instància $app de la classe \Slim\App. 

**De forma bàsica, el mètode té 2 paràmetres**: el route pattern (url) i el mètode callback a executar quan es crida a la funció (es reb la url que toca). *Podem assignar nom a les rutes per accedir més endavant.*

```php
$app = new \Slim\App();
$app->get('/books/{id}', function ($request, $response, $args) {
    // Show book identified by $args['id']
});
```

El mètode callback reb `$request, $response, $args`, i sempre ha de retornar la response.

- `$args` permet recuperar les "variables" que hem rebut en la url. Per exemple: `$args['id']`.

#### Controladors

https://www.slimframework.com/docs/v3/objects/router.html

Dins de la carpeta src/controller.

Cada controlador controla 1 ruta (relació 1:1).

**No estem limiatats a cridar a una funció callback** en el tractament d'una ruta, podem utilitzar **altres accions:**

- Class:method
- Invokable class



*(Aquestes classes estaran dins de la carpeta Controller)*

1) <u>Class:method</u>

En el segon paràmetre de la funció de la ruta (get, post, etc) indiquem el path fins arribar a la classe on tenim el mètode a cridar, dos punts i el mètode.

```php
$app->get('/user','PWBox\Controller\PostUserController:indexAction');
//alternativa per si tenim més classes en el fitxer del path
$app->get('/user','PWBox\Controller\PostUserController::class:indexAction');
```

2) <u>I</u><u>nvokable class</u>

En comptes de cridar a un mètode concret, fem la crida a una classe sencera, que s'encarregarà de fer la gestió de la ruta (per casos més complexes).

En el segon paràmetre de la funció de la ruta (get, post, etc) indiquem el path fins al fitxer de la classe (posem ::class per si tenim més d'una classe en el fitxer php). 

```php
$app->get('/hello/{name}','PWBox\Controller\HelloController::class');
```

Dins de la classe invokable tindrem un constructor que li permetrà accedir a dependencies.php:

```php
protected $container;

public function __construct(ContainerInterface $container) {
	$this->container = $container;//container permet accedir a dependecies.php
}
```

La classe té un mètode de gestió quan es crida a la classe invokable:

```php
public function __invoke($request, $response, $args) {
    // your code
    // to access items in the container... $this->container->get('');
    return $response;
}
```



#### Middleware

https://www.slimframework.com/docs/v3/concepts/middleware.html

Funcions que ens permeten fer accions abans o després d'executar la ruta (comprovacions, etc.).

Creem una classe pel middleware amb el mètode invoke, dins de /controller/middleware. També serà una invokable class.

```php
class ExampleMiddleware{
    public function __invoke($request, $response, $next) {
        $response->getBody()->write('BEFORE');
        $response = $next($request, $response);
        $response->getBody()->write('AFTER');

        return $response;//sempre fem return de response
    }
}
```

Afegim el middleware al fitxer de rutes, a la ruta que toqui:

```php
$app->get('/hello/{name}','PWBox\Controller\HelloController::class')
    ->add('PWBox\Controller\Middleware\UserLoggedMiddleware');
```



## Model slim

Conté tota la lògica de la aplicació (entitats, interfícies, serveis, etc.).