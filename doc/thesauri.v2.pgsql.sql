-- drop table ad_config;
CREATE TABLE ad_config (  
  id_config         character varying(60)           not null,  
  tipo_config	    character varying(20)           not null,
  is_activo	    boolean default true            not null,
  descripcion	    character varying(600),
  nombre	    character varying(100)          not null,
  orden	            int, 
  tipo_prop	    character varying(20)           not null,
  is_requerido	    boolean default false           not null,
  tipo_valor	    character varying(20)           not null, 
  CONSTRAINT ad_config_pkey PRIMARY KEY (id_config)   
);
ALTER TABLE ad_config OWNER TO thesauri;


-- drop table ad_usuario;
CREATE TABLE ad_usuario (
  id_usuario 	    serial                          NOT NULL,
  email             character varying(120)          NOT NULL,  
  is_activo         boolean                         NOT NULL DEFAULT true,  
  nombre            character varying(120)          NOT NULL,  
  app_role          character varying(40)           NOT NULL,
  clave             character varying(40)           NOT NULL,
  cambiar_clave     boolean                         NOT NULL DEFAULT true,
  nuevaclave_info   character varying(120),
  recibir_avisos    character varying(20)           NOT NULL DEFAULT 'DIARIO' CHECK(recibir_avisos IN ('TERMINO', 'DIARIO', 'SEMANAL') ),
  ultima_clave      date,   
  login_history     text,
  fecha_ingreso     timestamp     NOT NULL DEFAULT now(),  
  fecha_inactivo    timestamp,
  CONSTRAINT ad_usuario_pkey PRIMARY KEY (id_usuario),
  CONSTRAINT ad_usuario_email_uk UNIQUE (email)
);
ALTER TABLE ad_usuario OWNER TO thesauri;
ALTER TABLE ad_usuario_id_usuario_seq OWNER TO thesauri;

-- drop table th_thesaurus;
create table th_thesaurus (
  id_thesaurus      serial                          not null,
  nombre            character varying(100)          not null,
  notilde           character varying(100)          not null,  
  is_activo         boolean                         not null default true,
  is_publico        boolean                         not null default true,
  rdf_uri           character varying(100)          not null,      
  term_aprobados    int                             not null default 0,           
  term_pendientes   int                             not null default 0,
  ultima_actividad  timestamp,
  xml_iso25964      text,      
  aprobar_list      character varying(600),  
  id_propietario    integer,
  is_primario       boolean default false not null,
  
  iso25964_identifier   character varying(100),
  
  iso25964_description  text,
  iso25964_publisher    character varying(100),
  iso25964_rights       character varying(100),

  iso25964_license      character varying(100),
  iso25964_coverage     character varying(100),  
  iso25964_created      date,

  iso25964_subject      character varying(100),
  iso25964_language     character varying(100),  
  iso25964_source       character varying(100),
  
  iso25964_creator      character varying(100),
  iso25964_contributor  character varying(100),
  iso25964_type         character varying(100),
        
  fecha_ingreso     timestamp                        not null default now(),  
  fecha_modifica    timestamp,
  fecha_inactivo    timestamp,  
  CONSTRAINT th_thesaurus_pkey PRIMARY KEY (id_thesaurus),
  CONSTRAINT th_thesaurus_uk_nombre UNIQUE (nombre),
  CONSTRAINT th_thesaurus_propietario_fkey
    FOREIGN KEY (id_propietario) REFERENCES ad_usuario(id_usuario)
);
COMMENT ON TABLE public.th_thesaurus IS 'Vocabulario de un lenguaje de indexación controlado';
ALTER TABLE th_thesaurus OWNER TO thesauri;
ALTER TABLE th_thesaurus_id_thesaurus_seq OWNER TO thesauri;


-- drop table th_termino;
create table th_termino (
  id_termino        serial                          not null,
  nombre            character varying(100)          not null,
  estado_termino    character varying(20)           not null DEFAULT 'CANDIDATO' CHECK (estado_termino IN ('APROBADO', 'CANDIDATO', 'REEMPLAZADO', 'DEPRECADO') ),  
  rdf_uri           character varying(100)          not null,      
  iso25964_language character(2)                    not null DEFAULT 'es' CHECK (iso25964_language = lower(iso25964_language)),      
  id_thesaurus      integer                         not null,
  notilde           character varying(100)          not null,  
  descripcion       text,    
  dc_source         character varying(120)          not null,
  id_aprobador      integer,
  fecha_aprobado    timestamp,  	
  id_ingreso        integer,  
  fecha_ingreso     timestamp                       not null default now(),  
  id_modifica       integer,  
  fecha_modifica    timestamp without time zone,
  CONSTRAINT th_termino_pkey PRIMARY KEY (id_termino),
  CONSTRAINT th_termino_dominio_fkey
    FOREIGN KEY (id_thesaurus) REFERENCES th_thesaurus(id_thesaurus),
  CONSTRAINT th_termino_aprobador_fkey
    FOREIGN KEY (id_aprobador) REFERENCES ad_usuario(id_usuario),
  CONSTRAINT th_termino_ingreso_fkey
    FOREIGN KEY (id_ingreso) REFERENCES ad_usuario(id_usuario)
);
COMMENT ON TABLE public.th_termino IS 'Vocabulario de un lenguaje de indexación controlado';
ALTER TABLE th_termino ADD CONSTRAINT th_termino_rdf_uri_unique UNIQUE (id_thesaurus, rdf_uri);
ALTER TABLE th_termino OWNER TO thesauri;
ALTER TABLE th_termino_id_termino_seq OWNER TO thesauri;


// TE - Termino Equivalente, TR Relacionado, TG General
-- drop table th_relacion;
create table th_relacion (
  id_relacion       serial           not null,  
  tipo_relacion     character varying(20)           not null,  
  id_thesaurus      integer                         not null,
  id_termino        integer                         not null,
  id_termino_rel    integer                         ,
  CONSTRAINT th_relacion_pkey PRIMARY KEY (id_relacion),
  CONSTRAINT th_relacion_thesaurus_fkey FOREIGN KEY (id_thesaurus) REFERENCES th_thesaurus(id_thesaurus),
  CONSTRAINT th_relacion_termino_fkey FOREIGN KEY (id_termino) REFERENCES th_termino(id_termino),
  CONSTRAINT th_relacion_termino_rel_fkey FOREIGN KEY (id_termino_rel) REFERENCES th_termino(id_termino)
);
COMMENT ON TABLE public.th_relacion IS 'Relación con otros terminos';
ALTER TABLE th_relacion OWNER TO thesauri;
ALTER TABLE th_relacion_id_relacion_seq OWNER TO thesauri;

--  lang_list         character varying(100)          NOT NULL DEFAULT 'es',
--   dc_identifier     character varying(100),  
--   dc_publisher      character varying(100),
--   dc_rights         character varying(100),
--   dc_subject        character varying(100),
--   dc_source         character varying(100),    
--   dc_coverage       character varying(100),
--   dc_creator        character varying(100),  
--   dc_description    character varying(600),



-- drop table th_nota;
create table th_nota (
  id_nota           serial           not null,  
  tipo_nota         character varying(20)           not null,  
  contenido         text,
  dc_source         character varying(120),
  id_termino        integer                         not null,
  estado_nota       character varying(20),
  id_thesaurus      integer                         not null,
  id_aprobador      integer,
  fecha_aprobado    timestamp,
  id_ingreso        integer,  
  fecha_ingreso     timestamp                       not null default now(),  
  id_modifica       integer,  
  fecha_modifica    timestamp without time zone,  
  CONSTRAINT th_nota_pkey PRIMARY KEY (id_nota),
  CONSTRAINT th_nota_thesaurus_fkey FOREIGN KEY (id_thesaurus) REFERENCES th_thesaurus(id_thesaurus),
  CONSTRAINT th_nota_termino_fkey FOREIGN KEY (id_termino) REFERENCES th_termino(id_termino)  
);
COMMENT ON TABLE public.th_nota IS 'Nota tecnica';
ALTER TABLE th_nota OWNER TO thesauri;
ALTER TABLE th_nota_id_nota_seq OWNER TO thesauri;



-- drop table th_thesaurus_version;
create table th_thesaurus_version (
  id_version        integer                       not null, 
  id_thesaurus      integer                       not null,
  version_note      character varying(100),
  xml_data          text,  
  dc_title          character varying(100),
  date_created      date,
  date_modified     date,    
  identifier        character varying(20),  
  dc_coverage       character varying(100),
  dc_creator        character varying(100),  
  dc_description    character varying(600),
  dc_lang           character(2)                    not null default 'es' CHECK (dc_lang = lower(dc_lang)),      
  dc_publisher      character varying(100),
  dc_rights         character varying(100),
  dc_source         character varying(100),
  CONSTRAINT th_thesaurus_version_pkey PRIMARY KEY (id_thesaurus, id_version),
  CONSTRAINT th_thesaurus_vresion__thesaurus_fkey
    FOREIGN KEY (id_thesaurus) REFERENCES th_thesaurus(id_thesaurus)
);
COMMENT ON TABLE public.th_thesaurus_version IS 'Version del Thesaurus';
--COMMENT ON COLUMN my_table.my_column IS 'Employee ID number';

-- Items / Caracteristicas del thesauri (relacion)
-- drop table th_relacion;
create table th_relacion (
  id_relacion       character varying(20)           not null,
  descripcion       character varying(100)          not null,        
  code_identifier   character varying(2)            not null,
  tipo_relacion     character varying(20)           not null CHECK (tipo_relacion IN ('EQUIVALENCIA', 'JERARQUIA', 'ASOCIACION', 'CONCEPTUAL') ),
  nivel_relacion    character varying(20)           CHECK (nivel_relacion IN ('IS_PART_OF', 'IS_KIND_OF') ),
  role_relacion     character varying(20)           not null, 
  role_inversa      character varying(20)           not null, 
  grado_cardinal    character varying(20)           not null CHECK (grado_cardinal IN  ('0:1', '0:*', '1:1', '1:M') ),
  etiqueta_skos     character varying(20),
  etiqueta_iso      character varying(20)           not null,
  id_thesaurus      integer                         not null,
  id_relacion_padre character varying(20),  
  CONSTRAINT th_relacion_pkey PRIMARY KEY (id_relacion),
  CONSTRAINT th_relacion_thesaurus_fkey FOREIGN KEY (id_thesaurus) REFERENCES th_thesaurus(id_thesaurus),
  CONSTRAINT th_relacion_padre_fkey
    FOREIGN KEY (id_relacion_padre) REFERENCES th_relacion(id_relacion)
);





-- drop table th_thesauri;
create table th_thesauri (
  id_thesauri       serial                         not null,
  id_termino        integer                        not null,
  id_termino_rel    integer                        not null,
  id_relacion       character varying(20)          not null,
  estado_relacion   character varying(2)           not null,
  id_thesaurus        integer                        not null,
  fecha_aprobado    timestamp without time zone    not null,
  id_aprobador      integer,  
  fecha_ingreso     timestamp without time zone    not null,
  id_ingreso        integer ,  
  CONSTRAINT th_thesauri_pkey PRIMARY KEY (id_thesauri),
  CONSTRAINT th_thesauri_termino_uk UNIQUE (id_termino, id_termino_rel, id_relacion),
  CONSTRAINT th_thesauri_termino_fkey
    FOREIGN KEY (id_termino) REFERENCES th_termino(id_termino),
  CONSTRAINT th_thesauri_termino_rel_fkey
    FOREIGN KEY (id_termino_rel) REFERENCES th_termino(id_termino),  
  CONSTRAINT th_thesauri_dominio_fkey
    FOREIGN KEY (id_thesaurus) REFERENCES th_thesaurus(id_thesaurus),
  CONSTRAINT th_thesauri_relacion_fkey
    FOREIGN KEY (id_relacion) REFERENCES th_relacion(id_relacion),  
  CONSTRAINT th_thesauri_aprobador_fkey
    FOREIGN KEY (id_aprobador) REFERENCES ad_usuario(id_usuario),
  CONSTRAINT th_thesauri_ingreso_fkey
    FOREIGN KEY (id_ingreso) REFERENCES ad_usuario(id_usuario)  
);

