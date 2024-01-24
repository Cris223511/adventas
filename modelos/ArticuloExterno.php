<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Articulo
{
	//Implementamos nuestro constructor
	public function __construct()
	{
	}

	//Implementamos un método para insertar registros
	public function insertar($idusuario, $idcategoria, $idalmacen, $idmarcas, $idmedida, $codigo, $codigo_producto, $nombre, $stock, $stock_minimo, $precio_compra, $precio_venta, $descripcion, $talla, $color, $peso, $posicion, $imagen)
	{
		if (empty($imagen))
			$imagen = "product.jpg";

		$sql = "INSERT INTO articulo (idusuario,idcategoria,idalmacen,idmarcas,idmedida,codigo,codigo_producto,nombre,stock,stock_minimo,precio_compra,precio_venta,descripcion,talla,color,peso,posicion,imagen,estado)
		VALUES ('$idusuario','$idcategoria','$idalmacen','$idmarcas','$idmedida','$codigo','$codigo_producto','$nombre','$stock','$stock_minimo','$precio_compra','$precio_venta','$descripcion','$talla','$color','$peso','$posicion','$imagen','1')";
		return ejecutarConsulta($sql);
	}

	public function verificarCodigoExiste($codigo)
	{
		$sql = "SELECT * FROM articulo WHERE codigo = '$codigo'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El código ya existe en la tabla
			return true;
		}
		// El código no existe en la tabla
		return false;
	}

	public function verificarCodigoProductoExiste($codigo_producto)
	{
		$sql = "SELECT * FROM articulo WHERE codigo_producto = '$codigo_producto'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El código ya existe en la tabla
			return true;
		}
		// El código no existe en la tabla
		return false;
	}

	public function verificarStockMinimo($idarticulo, $cantidad)
	{
		$sql = "SELECT stock_minimo, stock FROM articulo WHERE idarticulo = '$idarticulo'";
		$resultado = ejecutarConsulta($sql);

		if (mysqli_num_rows($resultado) > 0) {
			$row = mysqli_fetch_assoc($resultado);
			$resultado = $row['stock'] - $cantidad;

			if ($resultado > 0 && $resultado <= $row['stock_minimo']) {
				return true; // Está dentro del rango del stock mínimo
			} else {
				return false; // Está fuera del rango del stock mínimo
			}
		} else {
			return false; // El artículo no existe en la tabla
		}
	}

	public function identificarStockMinimo($idarticulo)
	{
		$sql = "SELECT stock_minimo as stock_minimo FROM articulo WHERE idarticulo = '$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idarticulo, $idcategoria, $idalmacen, $idmarcas, $idmedida, $codigo, $codigo_producto, $nombre, $stock, $stock_minimo, $precio_compra, $precio_venta, $descripcion, $talla, $color, $peso, $posicion, $imagen)
	{
		$sql = "UPDATE articulo SET idcategoria='$idcategoria',idalmacen='$idalmacen',idmarcas='$idmarcas',idmedida='$idmedida',codigo='$codigo',codigo_producto='$codigo_producto',nombre='$nombre',stock='$stock',stock_minimo='$stock_minimo',precio_compra='$precio_compra',precio_venta='$precio_venta',descripcion='$descripcion',talla='$talla',color='$color',peso='$peso',posicion='$posicion',imagen='$imagen' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	public function verificarCodigoProductoEditarExiste($codigo_producto, $idarticulo)
	{
		$sql = "SELECT * FROM articulo WHERE codigo_producto = '$codigo_producto' AND idarticulo != '$idarticulo'";
		$resultado = ejecutarConsulta($sql);
		if (mysqli_num_rows($resultado) > 0) {
			// El código de artículo ya existe en la tabla
			return true;
		}
		// El código de artículo no existe en la tabla
		return false;
	}

	//Implementamos un método para desactivar registros
	public function desactivar($idarticulo)
	{
		$sql = "UPDATE articulo SET estado='0' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar registros
	public function activar($idarticulo)
	{
		$sql = "UPDATE articulo SET estado='1' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar registros
	public function eliminar($idarticulo)
	{
		$sql = "UPDATE articulo SET eliminado = '1' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idarticulo)
	{
		$sql = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql = "SELECT a.idarticulo,a.idcategoria,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,c.nombre as categoria,al.ubicacion as almacen,m.nombre as marca,me.nombre as medida,a.codigo,a.codigo_producto,a.nombre,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,a.estado FROM articulo a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN medidas me ON a.idmedida=me.idmedida LEFT JOIN categoria c ON a.idcategoria=c.idcategoria LEFT JOIN almacen al ON a.idalmacen=al.idalmacen LEFT JOIN marcas m ON a.idmarcas=m.idmarcas WHERE a.eliminado = '0' ORDER BY a.idarticulo DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listarPorUsuario($idalmacenSession)
	{
		$sql = "SELECT a.idarticulo,a.idcategoria,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,c.nombre as categoria,al.ubicacion as almacen,m.nombre as marca,me.nombre as medida,a.codigo,a.codigo_producto,a.nombre,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,a.estado FROM articulo a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN medidas me ON a.idmedida=me.idmedida LEFT JOIN categoria c ON a.idcategoria=c.idcategoria LEFT JOIN almacen al ON a.idalmacen=al.idalmacen LEFT JOIN marcas m ON a.idmarcas=m.idmarcas WHERE a.eliminado = '0'AND a.idalmacen = '$idalmacenSession' ORDER BY a.idarticulo DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos
	public function listarActivos()
	{
		$sql = "SELECT a.idarticulo,a.idcategoria,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,c.nombre as categoria,me.nombre as medida,a.codigo,a.codigo_producto,a.nombre,a.stock,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,a.estado FROM articulo a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN medidas me ON a.idmedida=me.idmedida LEFT JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.eliminado = '0' ORDER BY a.idarticulo DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos
	public function listarUsuarioActivos($idalmacenSession)
	{
		$sql = "SELECT a.idarticulo,a.idcategoria,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,c.nombre as categoria,me.nombre as medida,a.codigo,a.codigo_producto,a.nombre,a.stock,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,a.estado FROM articulo a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN medidas me ON a.idmedida=me.idmedida LEFT JOIN categoria c ON a.idcategoria=c.idcategoria WHERE a.eliminado = '0' AND a.idalmacen = '$idalmacenSession' ORDER BY a.idarticulo DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)
	public function listarActivosVenta()
	{
		$sql = "SELECT a.idarticulo,a.idcategoria,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,c.nombre as categoria, m.nombre as marca,me.nombre as medida,a.codigo,a.codigo_producto,a.nombre,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,a.estado FROM articulo a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN medidas me ON a.idmedida=me.idmedida LEFT JOIN categoria c ON a.idcategoria=c.idcategoria LEFT JOIN almacen al ON a.idalmacen=al.idalmacen LEFT JOIN marcas m ON a.idmarcas=m.idmarcas WHERE a.eliminado = '0' ORDER BY a.idarticulo DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)
	public function listarUsuarioActivosVenta($idusuario)
	{
		$sql = "SELECT a.idarticulo,a.idcategoria,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,c.nombre as categoria, m.nombre as marca,me.nombre as medida,a.codigo,a.codigo_producto,a.nombre,a.stock,a.stock_minimo,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,a.estado FROM articulo a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN medidas me ON a.idmedida=me.idmedida LEFT JOIN categoria c ON a.idcategoria=c.idcategoria LEFT JOIN almacen al ON a.idalmacen=al.idalmacen LEFT JOIN marcas m ON a.idmarcas=m.idmarcas WHERE a.eliminado = '0' AND a.idusuario = '$idusuario' ORDER BY a.idarticulo DESC";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)
	public function listarActivosVentaPorArticulo($idarticulo)
	{
		$sql = "SELECT a.idarticulo,a.idcategoria,CONCAT(u.nombre,' ',u.apellido) AS usuario, u.idusuario, u.cargo AS cargo,c.nombre as categoria,al.ubicacion as almacen,m.nombre as marca,me.nombre as medida,a.codigo,a.codigo_producto,a.nombre,a.stock,a.precio_compra,a.precio_venta,a.descripcion,a.talla,a.color,a.peso,a.posicion,a.imagen,a.estado FROM articulo a LEFT JOIN usuario u ON a.idusuario=u.idusuario LEFT JOIN medidas me ON a.idmedida=me.idmedida LEFT JOIN categoria c ON a.idcategoria=c.idcategoria LEFT JOIN almacen al ON a.idalmacen=al.idalmacen LEFT JOIN marcas m ON a.idmarcas=m.idmarcas WHERE a.eliminado = '0' AND a.idarticulo = $idarticulo ORDER BY a.idarticulo DESC";
		return ejecutarConsulta($sql);
	}

	/* ======================= SELECTS ======================= */

	public function listarTodosActivos()
	{
		$sql = "SELECT 'categoria' AS tabla, b.idcategoria AS id, b.nombre, u.nombre AS usuario, NULL AS ruc FROM categoria b LEFT JOIN usuario u ON b.idusuario = u.idusuario WHERE b.estado='1' AND b.eliminado='0'
			UNION ALL
			SELECT 'marcas' AS tabla, o.idmarcas AS id, o.nombre, u.nombre AS usuario, NULL AS ruc FROM marcas o LEFT JOIN usuario u ON o.idusuario = u.idusuario WHERE o.estado='Activado' AND o.eliminado='0'
			UNION ALL
			SELECT 'almacen' AS tabla, l.idalmacen AS id, l.ubicacion, u.nombre AS usuario, local_ruc AS ruc FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idusuario <> 0 AND l.estado='activado' AND l.eliminado='0'
			UNION ALL
			SELECT 'medida' AS tabla, m.idmedida AS id, m.nombre, u.nombre AS usuario, NULL AS ruc FROM medidas m LEFT JOIN usuario u ON m.idusuario = u.idusuario WHERE m.estado='activado' AND m.eliminado='0'";

		return ejecutarConsulta($sql);
	}

	public function listarTodosActivosPorUsuario($idusuario, $idalmacen)
	{
		$sql = "SELECT 'categoria' AS tabla, b.idcategoria AS id, b.nombre, u.nombre AS usuario, NULL AS ruc FROM categoria b LEFT JOIN usuario u ON b.idusuario = u.idusuario WHERE b.estado='1' AND b.eliminado='0'
			UNION ALL
			SELECT 'marcas' AS tabla, o.idmarcas AS id, o.nombre, u.nombre AS usuario, NULL AS ruc FROM marcas o LEFT JOIN usuario u ON o.idusuario = u.idusuario WHERE o.estado='Activado' AND o.eliminado='0'
			UNION ALL
			SELECT 'almacen' AS tabla, l.idalmacen AS id, l.ubicacion, u.nombre AS usuario, local_ruc AS ruc FROM almacen l LEFT JOIN usuario u ON l.idusuario = u.idusuario WHERE l.idalmacen='$idalmacen' AND l.idusuario <> 0 AND l.estado='activado' AND l.eliminado='0'
			UNION ALL
			SELECT 'medida' AS tabla, m.idmedida AS id, m.nombre, u.nombre AS usuario, NULL AS ruc FROM medidas m LEFT JOIN usuario u ON m.idusuario = u.idusuario WHERE m.estado='activado' AND m.eliminado='0'";

		return ejecutarConsulta($sql);
	}
}
