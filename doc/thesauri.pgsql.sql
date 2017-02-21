CREATE TABLE ad_usuario (
  id_usuario 	    serial                          NOT NULL,
  email             character varying(120)          NOT NULL,  
  is_activo         boolean                         NOT NULL DEFAULT true,  
  nombre            character varying(120)          NOT NULL,  
  app_role          character varying(40)           NOT NULL,
  clave             character varying(40)           NOT NULL,
  cambiar_clave     boolean                         NOT NULL DEFAULT true,
  nuevaclave_info   character varying(120),
  ultima_clave      timestamp without time zone,   
  fecha_ingreso     timestamp without time zone     NOT NULL DEFAULT now(),  
  fecha_inactivo    timestamp without time zone,
  CONSTRAINT ad_usuario_pkey PRIMARY KEY (id_usuario),
  CONSTRAINT ad_usuario_email_uk UNIQUE (email),
);

-- drop table th_dominio;
create table th_dominio (
  id_dominio        serial                          not null,
  nombre            character varying(100)          not null,
  descripcion       character varying(600)          not null,
  is_activo         boolean                         not null default true,
  is_publico        boolean                         not null default true,
  fecha_inactivo    timestamp without time zone,  
  CONSTRAINT ad_dominio_pkey PRIMARY KEY (id_dominio)   
);

-- drop table th_termino;
create table th_termino (
  id_termino        serial                          not null,
  nombre            character varying(100)          not null,
  estado_termino    character varying(2)            not null,  
  id_dominio        integer                         not null,
  notilde           character varying(100)          not null,  
  descripcion       character varying(600)          not null,
  id_aprobador      integer ,
  fecha_aprobado    timestamp without time zone     not null,  
  id_ingreso        integer ,  
  fecha_ingreso     timestamp without time zone     not null default now(),  
  CONSTRAINT th_termino_pkey PRIMARY KEY (id_termino),
  CONSTRAINT th_termino_dominio_fkey
    FOREIGN KEY (id_dominio) REFERENCES th_dominio(id_dominio),
  CONSTRAINT th_termino_aprobador_fkey
    FOREIGN KEY (id_aprobador) REFERENCES ad_usuario(id_usuario),
  CONSTRAINT th_termino_ingreso_fkey
    FOREIGN KEY (id_ingreso) REFERENCES ad_usuario(id_usuario)
);


-- Items / Caracteristicas del thesauri (relacion)
-- drop table th_relacion
create table th_relacion (
  id_relacion       character varying(20)           not null,
  descripcion       character varying(100)          not null,        
  tipo_item         character varying(20)           not null,
  id_dominio        integer                         not null,
  CONSTRAINT th_relacion_pkey PRIMARY KEY (id_relacion),
  CONSTRAINT th_relacion_dominio_fkey FOREIGN KEY (id_dominio) REFERENCES th_dominio(id_dominio)
);

-- drop table th_thesauri;
create table th_thesauri (
  id_thesauri       serial                         not null,
  id_termino        integer                        not null,
  id_termino_rel    integer                        not null,
  id_relacion       character varying(20)          not null,
  estado_relacion   character varying(2)           not null,
  id_dominio        integer                        not null,
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
    FOREIGN KEY (id_dominio) REFERENCES th_dominio(id_dominio),
  CONSTRAINT th_thesauri_relacion_fkey
    FOREIGN KEY (id_relacion) REFERENCES th_relacion(id_relacion),  
  CONSTRAINT th_thesauri_aprobador_fkey
    FOREIGN KEY (id_aprobador) REFERENCES ad_usuario(id_usuario),
  CONSTRAINT th_thesauri_ingreso_fkey
    FOREIGN KEY (id_ingreso) REFERENCES ad_usuario(id_usuario)  
);

