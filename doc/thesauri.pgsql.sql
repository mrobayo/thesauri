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
  recibir_avisos    character varying(20)           NOT NULL DEFAULT 'DIARIO' CHECK(recibir_aviso IN ('TERMINO', 'DIARIO', 'SEMANAL') ),
  ultima_clave      timestamp without time zone,   
  login_history     text,
  fecha_ingreso     timestamp without time zone     NOT NULL DEFAULT now(),  
  fecha_inactivo    timestamp without time zone,
  CONSTRAINT ad_usuario_pkey PRIMARY KEY (id_usuario),
  CONSTRAINT ad_usuario_email_uk UNIQUE (email)
);

-- drop table th_thesaurus;
create table th_thesaurus (
  id_thesaurus      serial                          not null,
  dc_title          character varying(100)          not null,  
  is_activo         boolean                         not null default true,
  is_publico        boolean                         not null default true,
  lang_list         character varying(100)          NOT NULL DEFAULT 'es',
  aprobar_list      character varying(100),  
  descripcion       character varying(600),    
  id_creador        integer,  
  fecha_creador     timestamp without time zone     not null default now(),  
  fecha_modifica    timestamp without time zone,
  fecha_inactivo    timestamp without time zone,  
  CONSTRAINT th_thesaurus_pkey PRIMARY KEY (id_thesaurus),
  CONSTRAINT th_thesaurus_propietario_fkey
    FOREIGN KEY (id_creador) REFERENCES ad_usuario(id_usuario),
  CONSTRAINT th_thesaurus_iso_idioma_pref_check CHECK (iso_idioma_pref::text = lower(iso_idioma_pref::text)),
  CONSTRAINT th_thesaurus_iso_locode_pref_check CHECK (iso_locode_pref::text = upper(iso_locode_pref::text))
);
COMMENT ON TABLE public.th_thesaurus IS 'Vocabulario de un lenguaje de indexación controlado';

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

-- drop table th_termino;
create table th_termino (
  id_termino        serial                          not null,
  nombre            character varying(100)          not null,
  estado_termino    character varying(20)           not null DEFAULT 'CANDIDATO' CHECK (estado_termino IN ('APROBADO', 'CANDIDATO', 'REEMPLAZADO', 'DEPRECADO') ),  
  is_concepto_top   boolean                         not null DEFAULT false,  
  iso_lang          character(2)                    not null DEFAULT 'es' CHECK (iso_lang = lower(iso_idioma)),      
  iso_locode        character(2)                    not null DEFAULT 'EC' CHECK (iso_locode = upper(iso_locode)),
  id_thesaurus      integer                         not null,
  notilde           character varying(100)          not null,  
  descripcion       character varying(600)          not null,    
  id_aprobador      integer ,
  fecha_aprobado    timestamp without time zone     not null,  	
  id_ingreso        integer ,  
  fecha_ingreso     timestamp without time zone     not null default now(),  
  id_modifica       integer ,  
  fecha_modifica    timestamp without time zone,
  CONSTRAINT th_termino_pkey PRIMARY KEY (id_termino),
  CONSTRAINT th_termino_dominio_fkey
    FOREIGN KEY (id_thesaurus) REFERENCES th_thesaurus(id_thesaurus),
  CONSTRAINT th_termino_aprobador_fkey
    FOREIGN KEY (id_aprobador) REFERENCES ad_usuario(id_usuario),
  CONSTRAINT th_termino_ingreso_fkey
    FOREIGN KEY (id_ingreso) REFERENCES ad_usuario(id_usuario)
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

