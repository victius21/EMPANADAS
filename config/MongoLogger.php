<?php
// config/MongoLogger.php

class MongoLogger {
    private MongoDB\Driver\Manager $manager;
    private string $dbName;

    public function __construct() {
        // Variables de entorno definidas en Render
        $uri = getenv('MONGO_URI') ?: 'mongodb://localhost:27017';
        $this->dbName = getenv('MONGO_DB') ?: 'empanadas_nosql';

        $this->manager = new MongoDB\Driver\Manager($uri);
    }

    /**
     * Guarda un evento genérico en la colección 'eventos'.
     */
    public function logEvent(string $tipo, array $data = []): void {
        try {
            $bulk = new MongoDB\Driver\BulkWrite;

            $doc = array_merge($data, [
                'tipo'      => $tipo,
                'fecha'     => new MongoDB\BSON\UTCDateTime(),
                'ip'        => $_SERVER['REMOTE_ADDR']     ?? null,
                'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            ]);

            $bulk->insert($doc);

            $this->manager->executeBulkWrite(
                $this->dbName . '.eventos',
                $bulk
            );
        } catch (\Throwable $e) {
            // No rompemos la app si falla Mongo; solo lo ignoramos
        }
    }

    /**
     * Guarda un cambio de estado de pedido en 'historial_pedidos'.
     */
    public function logCambioEstadoPedido(int $idPedido, ?string $estadoAnterior, string $estadoNuevo): void {
        try {
            $bulk = new MongoDB\Driver\BulkWrite;

            $bulk->insert([
                'id_pedido'       => $idPedido,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => $estadoNuevo,
                'fecha'           => new MongoDB\BSON\UTCDateTime(),
            ]);

            $this->manager->executeBulkWrite(
                $this->dbName . '.historial_pedidos',
                $bulk
            );
        } catch (\Throwable $e) {
            // Igual, no rompemos nada
        }
    }
}
