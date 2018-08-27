(:
Base de datos XML
<alojamientos>
    <alojamiento tipo="hotel">
        <nombre>Sana Malhoa</nombre>
        <cif>12345</cif>
        <dir>Av Jose Malhoa</dir>
        <tel>1111</tel>
        <habitaciones>
            <habitacion id="01" est="libre">
                <precio>90</precio>
            </habitacion>
        </habitaciones>
    </alojamiento>
     <alojamiento tipo="hotel">
        <nombre>AC</nombre>
        <cif>12346</cif>
        <dir>Calle los vascos</dir>
        <tel>2222</tel>
        <habitaciones>
            <habitacion id="02" est="libre">
                <precio>75</precio>
            </habitacion>
        </habitaciones>
    </alojamiento>
     <alojamiento tipo="hotel">
        <nombre>Yo</nombre>
        <cif>12347</cif>
        <dir>Yo y mi mismidad</dir>
        <tel>2222</tel>
        <habitaciones>
            <habitacion id="02" est="ocupado">
                <precio>120</precio>
            </habitacion>
        </habitaciones>
    </alojamiento>
</alojamientos>
:)
(: 
a) El nombre de cada hotel con su telefono
b) Nombre del hotel y numero de habitaciones.
c) Para cada hotel, el precio mnimo de sus habitaciones y maximo.
d) El nombre y el telefono de los hoteles con habitaciones libres
 :)
for $i in doc("hoteles.xml")/alojamientos/alojamiento
where $i[@tipo="hotel"]
return <hotel>
    <nom>{$i/nombre}</nom>
    <tel>{$i/tel}</tel>
    
</hotel>
 for $i in doc("hoteles.xml")/alojamientos/alojamiento
 let $numH:=count($i/habitaciones/habitacion)
 where $i[@tipo="hotel"]
 return <hotel>
    <nom>{$i/nombre}</nom>
    <hab>{$numH}</hab>
</hotel>
 
 for $i in doc("hoteles.xml")/alojamientos/alojamiento
 let $mxP:= max($i/habitaciones/habitacion)
 let $minP:=min($i/habitaciones/habitacion)
 where $i[@tipo="hotel"]
 return <hotel>
    <nom>{$i/nom}</nom>
    <mp>{$mxP}</mp>
    <min>{$minP}</min>
</hotel> 

 for $i in doc("hoteles.xml")/alojamientos/alojamiento
 where $i[@tipo="hotel"], some $j in $i//habitacion 
 satisfies $j[@est="libre"] 
 return <hotel>
    <nom>{$i/nombre}</nom>
    <tel>{$i/tel}</tel>
 </hotel>
