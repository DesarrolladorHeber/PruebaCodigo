CREATE TABLE lista_precios_proveedores (
    cod_lista integer NOT NULL,
    codigo_producto character varying(40) NOT NULL,
    precio_bruto numeric(12,0) DEFAULT 0 NOT NULL,
    iva numeric(3,0) DEFAULT 0,
    precio_neto numeric(12,0) DEFAULT 0 NOT NULL,
    unidad_empaque numeric(12,0) DEFAULT 0 NOT NULL
);


COMMENT ON TABLE lista_precios_proveedores IS 'Lista de precios para los productos por proveedor.';

COMMENT ON COLUMN lista_precios_proveedores.cod_lista IS 'Llave foranea de la lista.';

COMMENT ON COLUMN lista_precios_proveedores.codigo_producto IS 'Codigo del producto.';

COMMENT ON COLUMN lista_precios_proveedores.precio_bruto IS 'Precio sin iva del producto';

COMMENT ON COLUMN lista_precios_proveedores.iva IS 'iva que tendra el producto';

COMMENT ON COLUMN lista_precios_proveedores.precio_neto IS 'Precio incluido iva';

COMMENT ON COLUMN lista_precios_proveedores.unidad_empaque IS 'Unidades por las que viene el producto.';

ALTER TABLE ONLY lista_precios_proveedores
    ADD CONSTRAINT lista_precios_proveedores_pkey PRIMARY KEY (cod_lista, codigo_producto);

ALTER TABLE ONLY lista_precios_proveedores
    ADD CONSTRAINT cod_lista FOREIGN KEY (cod_lista) REFERENCES listas_proveedores(cod_lista) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY lista_precios_proveedores
    ADD CONSTRAINT codigo_producto FOREIGN KEY (codigo_producto) REFERENCES inventarios_productos(codigo_producto) ON UPDATE CASCADE ON DELETE CASCADE;
