# Application-configuration

## Project status

Actief in ontwikkeling.

## Te bereiken doel

De module moet het mogelijk maken voor de ontwikkelaar applicatie instellingen uit diverse bronnen te laden.
Daarnaast dient de module de ontwikkelaar te voorzien van een enkele en eenvoudige methode om applicatie
instellingen uit diverse bronnen uit te lezen. Nadat de applicatie instellingen geladen zijn door de module is
deze methode voor alle applicatie instellingen hetzelfde.

Daarnaast vind ik het belangerijk dat waar mogelijk applicatie instellingen "strong-typed" worden teruggegeven.
Bijvoorbeeld: `1.5` wordt een double, `true` wordt een boolean. `'2020-01-03'` wordt een \DateTime instance. 

De module moet te gebruiken zijn in PHP webapplicaties en PHP CLI scripts. De eerste versie, die momenteel in 
ontwikkeling is, moet het mogelijk maken om applicatie instellingen uit diverse bestandstypes te laden. 
Nu worden de volgende bestandstypes ondersteund:

* .env
* .ini
* .xml
* .yaml/.yml
* .json

## Applicatie onderdelen

- ApplicationConfiguration.php - de module is via een instantie van deze class te bedienen.

## Applicatie entiteiten

Hieronder zal ik een korte beschrijving geven van de basis entiteiten van deze module.

#### Resource

bronnen waaruit applicatie instellingen geladen moeten worden. Nu kunnen dit zijn:

- Bestanden
- Mappen - Hieruit kunnen een of meerdere bestanden worden geladen

#### Configuration Resource

Representatie van een resource waaruit applicatie instellingen gelezen kunnen worden.

Zie: `src/Resources/Resource/Files/FileConfigurationResource.php`

#### Resource Handler 

Resource handlers bieden de mogelijkheid om applicatie instellingen van een `Configuration Resource` op te vragen.
Daarnaast representeert een resource handler de "staat" van een geladen resource. De ontwikkelaar kan
een resource handler te bevragen om te zien als de resource veranderd is vergeleken met de laatste keer dat de resource
geladen is door de module. Dit biedt de mogelijkheid om specifieke applicatie instellingen te herladen.

Zie: `src/Resources/Handlers/Files/FileResourceHandler.php`

##### Handler Strategies

Een Handler strategy geeft een Resource handler de functionaliteit om de applicatie instellingen uit te lezen.
Een resource handler wordt geinitialiseert met een handler strategy dat past bij de gegeven resource.

Zie:

- `src/Resources/Handlers/Factories/Strategies/Files/EnvHandlerStrategy.php`
- `src/Resources/Handlers/Factories/Strategies/Files/IniHandlerStrategy.php`
- `src/Resources/Handlers/Factories/Strategies/Files/JsonHandlerStrategy.php`
- `src/Resources/Handlers/Factories/Strategies/Files/XmlHandlerStrategy.php`
- `src/Resources/Handlers/Factories/Strategies/Files/YamlHandlerStrategy.php`

#### Entries Flattener

Het uitlezen van de applicatie instellingen levert verschillende data structuren op.
Het is de taak van de Entries Flattener om de verschillende data structuren om te zetten
naar een enkele data structuur waardoor alle applicatie instellingen op dezelfde 
manier verder verwerkt kunnen worden.

Zie:

- `src/Entries/Flattener/ConfigurationEntriesFlattener.php`
- `src/Entries/Flattener/EnvConfigurationEntriesFlattener.php`

##### Resource Entry

Een Resource Entry representeert een enkele applicatie instelling. Hierbij draait het hoofdzakelijk om de
volgende onderdelen:

- Path     - Identifier van de applicatie setting. (bijvoorbeeld: `company.employees.employee.name`)
- Waarde   - De waarde die de applicatie instelling presenteeert. (bijvoorbeeld: `John Doe`)

**Voorbeeld: example-resource.xml**

    ....
    <company>
       <employees>
            <employee>
                <name>John Doe</name>
            <employee>
        </employees>
    </company>
    ....

Zie: src/Entries/Entry/ResourceEntry.php
