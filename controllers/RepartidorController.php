<?php
class RepartidorController{
 private $pdo;
 function __construct($pdo){$this->pdo=$pdo;}
 function panel(){
  $sql="SELECT p.id,p.estado,p.total,c.nombre,
  GROUP_CONCAT(CONCAT(pd.cantidad,'x ',pr.nombre) SEPARATOR ' | ') items
  FROM pedidos p 
  JOIN clientes c ON c.id=p.cliente_id
  JOIN pedido_detalle pd ON pd.pedido_id=p.id
  JOIN productos pr ON pr.id=pd.producto_id
  WHERE p.estado IN ('listo','en_preparacion')
  GROUP BY p.id";
  $pedidos=$this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  include __DIR__.'/../views/repartidor_panel.php';
 }
}
