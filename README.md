# thesauri
TESAURO DE TÉRMINOS CRIMINALES BASADO EN WEB SEMÁNTICA

ISO 25964: Thesauri and interoperability with other vocabularies
- Part 1: Thesauri for information retrieval
- Part 2: Interoperability with other vocabularies
https://www.iso.org/obp/ui/#iso:std:iso:25964:-1:ed-1:v1:en:term:2.45
https://www.iso.org/obp/ui/#iso:std:iso:25964:-1:ed-1:v1:en:term:2.45

# DCMI
Dublin Core Metadata Initiative
Title: 	DCMI Metadata Terms
http://dublincore.org/documents/dcmi-terms/

# RDF
http://www.pearltrees.com/u/12835578-rdf-schema-wikipedia


# El modelo representa
- términos de un tesauro (tesauro Concepto) 
- jerárquica tradicional y las relaciones asociativas y tipos definidos por el usuario de las relaciones entre conceptos 
- anotaciones textuales tales como notas (notas de alcance), Cambiar los avisos, las definiciones, editorial Notas ... 
- términos preferidos (descriptores) y no se prefiere Nombres (no-descriptores) y no se prefiere Composites (referencias en combinaciones de términos) 
- filas plazo (arrays) y las etiquetas de nodo 
- grupos de conceptos, la aplicación específica tipificable

Los aspectos básicos del diseño son los siguientes:
– que el dato esté en la Web;
– que sea un dato interpretable por las máquinas;
– que no esté en formato propietario;
– que se use el estándar rdf;
– que sean enlazados mediante rdf. 


# Implementaciones
1. El metatesauro de medicina UMLS (Unified medical language system), de la US National Library of Medicine, 
   que integra 100 tesauros de medicina y cinco millones de conceptos.
   [http://www.nlm.nih.gov/research/umls](http://www.nlm.nih.gov/research/umls)

2. El proyecto DBpedia es un ejemplo de este planteamiento. Trabaja para extraer datos de la Wikipedia
   y ponerlos a disposición web de forma estructurada, mostrando los resultados de búsqueda con [Zitgist](http://zitgist.com). 
  
3. Otros ejemplos de resultados de linked data significativos son Geonames y The Data Hub.

# Ejemplos
El modo de unir diferentes vocabularios puede hacerse con mapeos uno a uno de los esquemas, pero también mediante
una ontología de alto nivel o una ontología de referencia. Un ejemplo es el proyecto Umbel (Upper mapping and binding
exchange layer) diseñado para ayudar a los contenidos a interactuar. Se establecen vínculos entre la ontología conceptual
de referencia y los vocabularios controlados o de dominio y conjuntos de datos. Incluye vocabularios de metadatos
y ontologías de amplia difusión como SKOS, WordNet, RSS, FOAF, Sioc, PIM, GeoNames, y OpenCyc, entre otras.

 * [http://rdfs.org/sioc/spec](http://rdfs.org/sioc/spec)

Ontologías y vocabularios de metadatos se vinculan mediante diferentes eƟquetas para unir conceptos idénticos,
ayudando así a la fusión y coexistencia de diferentes conjuntos de etiquetas. Es decir, el objeƟvo no es transformar
los recursos, sino crear un mapa de su significado como en TagCommons o Folcsonomías. Otro proyecto relacionado
es Semse, un sistema que permite la representación y recuperación conceptual de esquemas de metadatos mediante
su mapeo contra una ontología de alto nivel (Palacios, 2010).


ISO. BS 8723-4:2007 Structured Vocabularies for information retrieval. Guide. Interoperability between vocabularies. British Standards Intitution: London, 2007, 62. ISBN: 978 0 580 63073 6

ISO. ISO 25964-1:2011. Thesauri and interoperability with
other vocabularies. Part 1: Thesauri for informaƟon retrieval,
2011.



# RDF
RDF es un lenguaje para representar conocimiento en la Word Wide Web basado en XML. Permite realizar afirmaciones sobre recursos. 
Mediante RDF es posible representar cualquier tipo de propiedad de un recurso
