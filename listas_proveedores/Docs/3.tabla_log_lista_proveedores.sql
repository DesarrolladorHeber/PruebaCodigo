CREATE TABLE log_lista_proveedores (
    cod_log integer NOT NULL,
    cod_lista integer NOT NULL,
    codigo_producto character varying(40) NOT NULL,
    tipo_log character varying(1) NOT NULL,
    fecha_log timestamp without time zone DEFAULT now() NOT NULL,
    usuario integer
);


COMMENT ON TABLE log_lista_proveedores IS 'Tabla para almacenar los cambios realizados en las listas de precios de los proveedores.';

COMMENT ON COLUMN log_lista_proveedores.cod_log IS 'Codigo interno serial de la tabla.';

COMMENT ON COLUMN log_lista_proveedores.cod_lista IS 'Codigo de la lista  a la cual se le estan haciendo los cambios.';

COMMENT ON COLUMN log_lista_proveedores.codigo_producto IS 'El codigo de producto sera 0000 cuando se hace un cambio en toda la lista.';

COMMENT ON COLUMN log_lista_proveedores.tipo_log IS '1: cuando se realiza un insert, 2: cunado se realiza un update, 3: cuando se realiza un delete.';

COMMENT ON COLUMN log_lista_proveedores.usuario IS 'Usuario que realiza el cambio.';

CREATE SEQUENCE log_lista_proveedores_cod_lista_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE log_lista_proveedores_cod_lista_seq OWNED BY log_lista_proveedores.cod_lista;

CREATE SEQUENCE log_lista_proveedores_cod_log_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE log_lista_proveedores_cod_log_seq OWNED BY log_lista_proveedores.cod_log;

ALTER TABLE ONLY log_lista_proveedores ALTER COLUMN cod_log SET DEFAULT nextval('log_lista_proveedores_cod_log_seq'::regclass);

ALTER TABLE ONLY log_lista_proveedores
    ADD CONSTRAINT log_lista_proveedores_pkey PRIMARY KEY (cod_log, cod_lista, codigo_producto);

ALTER TABLE ONLY log_lista_proveedores
    ADD CONSTRAINT cod_lista FOREIGN KEY (cod_lista) REFERENCES listas_proveedores(cod_lista) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY log_lista_proveedores
    ADD CONSTRAINT codigo_producto FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY log_lista_proveedores
    ADD CONSTRAINT usuario FOREIGN KEY (usuario) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

