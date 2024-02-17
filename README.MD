# Burger Express

- Fullstack webová aplikace

Tématem této webové aplikace je objednávkový systém. Nachází se zde 4 uživatelské role,

 - - SuperAdmin, Admin, Dodavatel a Konzument.

 Konzument může přidat nebo odebrat zboží z košíku a následně jej objednat. Vidí své dokončené objednávky v uživatelské sekci a může si změnit heslo stejně tak, jak to umí ostatní uživatelské role. Dodavatel může přidávat nové produkty a upravovat množství naskladněných produktů, popřípadě je odstranit z nabídky produktů. Admin má právo ke změně uživatelské role Konzument na Dodavatel a stejně tak obráceně u uživatelů s těmito rolemi. SuperAdmin může provádět vše, co mohou uživatelé s ostatními rolemi, navíc ale může jmenovat uživatele Adminem, a naopak tuto roli odebírat.

## Použité technologie 
- HTML5
- CSS
- JavaScript
- PHP
- Ajax
- Apache
- MariaDB

### Struktura aplikace následuje konvenci zvanou 
- Model-View-Controller (MVC). 
- - MVC konvence, tedy Model-View-Controller je metoda, kterou je možné použít při tvorbě webové aplikace. Model znázorňuje datový model, View šablonu a Controller kontrolér dané stránky. V případě této aplikace komunikuje kontrolér s datovým modelem, získá z něj data a ty pošle do šablony, která je zpracuje a vykreslí na danou stránku.

Dále je použit composer, což je nástroj sloužící pro správu závislostí pro PHP projekty. V této aplikaci je použito několik knihoven, kterými jsou 
- BootStrap, FontAwesome, JQuery, PopperBundle, HTMLPurifier, Summernote a SweetAlert. 

HTML5 tvoří tzv. „holou“ kostru daných stránek aplikace. Pro jejich zvýraznění jsou použity kaskádové styly, neboli CSS. Pro lepší vyobrazení daných komponent je použita knihovna BootStrap a pro zobrazení ikon je použita knihovna FontAwesome. Pro interaktivní zobrazení komponent je využita knihovna JQuery, dále pro výpis alertů je použita knihovna SweetAlert a pro vytvoření WYSIWYG editoru je použita knihovna Summernote. Pro získání kódu z textu daného editoru a pro ošetření XSS útoků je použita knihovna HTMLPurifier.

Pro načtení stránek je použit soubor index.php, který si načte potřebná nastavení a ve kterém je následně vytvořena instance třídy, která se stará o spouštění aplikace, a následně je zavolána její funkce sloužící pro spuštění dané aplikace.

Pro práci s databází je použita relační databáze MariaDB. V databázi jsou vytvořeny tabulky uživatel, právo, objednávka, obsahuje a produkt s daty, se kterými dále aplikace pracuje.

O responzivitu stránek se stará převážně knihovna BootStrap, ale také kaskádové styly. Jde tedy o to, aby se stránky zobrazily správně jak na obrazovce počítače, tak i na obrazovce menšího zařízení, například na mobilním telefonu.

 
## Spuštění aplikace
Pro spuštění aplikace je nejprve potřeba ve složce composer spustit příkazovou řádku a v ní spustit příkaz composer install - 
   stažení composeru:
https://getcomposer.org/Composer-Setup.exe . 
Dále se ve složce database nachází SQL skript, který vytvoří databázi ´web´ a vloží do ní tabulky a data. Testovací data jsou:

- E-mail		x		*Heslo*
- superadmin@zcu.cz	 x	*superadmin*
- admin@zcu.cz		 x  *admin*
- dodavatel@zcu.cz	 x	*dodavatel*
- konzument@zcu.cz	 x	*konzument*
