-- DROP TABLE lista_precios_proveedores;
-- DROP TABLE log_lista_proveedores;
-- DROP TABLE listas_proveedores;

CREATE TABLE listas_proveedores (
    cod_lista integer NOT NULL,
    tipo_id_tercero character varying(3) NOT NULL,
    tercero_id character varying(32) NOT NULL,
    fecha_creacion timestamp without time zone DEFAULT now(),
    estado character varying(1) DEFAULT 0,
    usuario integer
);


COMMENT ON TABLE listas_proveedores IS 'Lista de precios de proveedores.';

COMMENT ON COLUMN listas_proveedores.cod_lista IS 'Codigo interno de la lista';

COMMENT ON COLUMN listas_proveedores.tipo_id_tercero IS 'Tipo de identificacion del tercero proveedor ';

COMMENT ON COLUMN listas_proveedores.tercero_id IS 'Identificacion del proveedor';

COMMENT ON COLUMN listas_proveedores.fecha_creacion IS 'Fecha creacion de la lista';

COMMENT ON COLUMN listas_proveedores.estado IS 'estado de la lista 0 inactiva  1 activa';

COMMENT ON COLUMN listas_proveedores.usuario IS 'Usuario que creo la lista.';


CREATE SEQUENCE listas_proveedores_cod_lista_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE listas_proveedores_cod_lista_seq OWNED BY listas_proveedores.cod_lista;

ALTER TABLE ONLY listas_proveedores ALTER COLUMN cod_lista SET DEFAULT nextval('listas_proveedores_cod_lista_seq'::regclass);

ALTER TABLE ONLY listas_proveedores
    ADD CONSTRAINT listas_proveedores_pkey PRIMARY KEY (cod_lista);

ALTER TABLE ONLY listas_proveedores
    ADD CONSTRAINT proveedor FOREIGN KEY (tipo_id_tercero, tercero_id) REFERENCES terceros(tipo_id_tercero, tercero_id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY listas_proveedores
    ADD CONSTRAINT usuario FOREIGN KEY (usuario) REFERENCES system_usuarios(usuario_id) ON UPDATE CASCADE ON DELETE CASCADE;

