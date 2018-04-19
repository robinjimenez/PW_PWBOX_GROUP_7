# PROJECTES WEB II



### Vagrant

*Vagrant* és un **gestor** de màquines virtuals. Permet fer accions relacionades amb màquines virtuals a través del Terminal (instalar-les, gestionar-les…).

*HomeStead* és una **Box** concreta (un paquet) de Vagrant, on hi ha una màquina virtual amb software (Linux, mySQL, PHP...).

La màquina virtual s'instala en un **Provider**, en aquest cas *VirtualBox*.



### Homestead.yaml

Fitxer on podem configurar coses de la màquina virtual. Es poden posar les **carpetes que volem compartir** entre el meu ordinador real i la màquina virtual. *També configuració de sites de Nginx i BBDD.*



### Terminal

Per utilitzar la màquina virtual:

- `cd /Users/jordi/HomeStead`
- `Encendre VM: vagrant up`
- `Estat VM: vagrant status`
- `Accedir VM: vagrant ssh`
- `Sortir VM: exit`
- `Apagar VM: vagrant halt`




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

FUNCIONS TERMINAL
touch <file.html> //Crear un arxiu a la carpeta actual
clear //netejar terminal
cd
cd ..
open
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



## PHP

Llenguatge per a gestionar servidors. **S'executa a través del terminal:** 

```
php scriptName.php 
```

El codi va entre tags  `<?php       -codi-         ?>`



#### Variables

- Tipus bàsics: **boolean, integer, float, string, array, object, null**
- S'escriuen amb un dollar davant**. Per exemple: `$message = "Hello world";`
- Hi ha variables predefinides, les Superglobals.
- Constants en majúscules i desfinició per clau-valor: `define('NAME', 'Jordi');`
- En les funcions podem **declarar** el pas dels paràmetres per valor **o per referència**, com en C: `public function changeName(&$name){...}` 

Printar text: `echo "Hello world";`

Printar variables: `var_dump($message);`

Per reconéixer variables en un text escrit calen doble cometes. `echo "Vull printar $message";`



<u>Arrays</u>

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



<u>Object</u>

Instàncies d'una classe.

Per accedir a mètodes de la classe no s'utilitza el punt. **S'utilitza la fletxa**.

Per crear la classe:

```php
class User {
    private $name;
    
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }     
}

$jordi = new User("Jordi");
echo $jordi->getName();
```



### Files

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

