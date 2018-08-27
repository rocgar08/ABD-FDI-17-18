(:Base de datos XML
<planetas>
    <planeta >
        <nombre>Tierra</nombre>
        <diametro>6371</diametro>
        <masa>59736</masa>
        <satelites>
            <satelite>
                <nombre>Luna</nombre>
            </satelite>
        </satelites>
    
    </planeta>
    
     <planeta  >
        <nombre>Júpiter</nombre>
        <diametro>192484</diametro>
        <masa>1899</masa>
        <satelites>
            <satelite>
                <nombre>Calisto</nombre>
            </satelite>
        </satelites>
    
    </planeta>
    
      <planeta >
        <nombre>Saturno</nombre>
        <diametro>120536</diametro>
        <masa>5688</masa>
        <satelites>
            <satelite>
                <nombre>Fobos</nombre>
            </satelite>
        </satelites>
    
    </planeta>
</planetas>:)
(:Consultas XQUERY:)
xquery version "3.0";

for $i in doc("planetas.xml")/planetas/planeta
where $i/masa > 1
return <nombre>{$i/nombre}</nombre>

for $i in doc("planetas.xml")/planetas/planeta
return <resultado>
        <plan>{$i/nombre}</plan>
        {for $info in doc("planetas.xml")/planetas/planeta
              where $info/nombre = $i/nombre
              return <nom>{$info/satelites/satelite}</nom>}
    </resultado>
