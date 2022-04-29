<?php

namespace Davinci\Storage;


class FileUpload
{
    /** @var array|string El archivo de $_FILES o un string con un base64. */
    protected $file;

    /**
     * FileUpload constructor.
     *
     * @param array|string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Guarda un archivo subido a su ubicación final.
     * Si es el $file provisto en el constructor es un string, lo va a tratar de parsear como un base64.
     * Si es un array, asume que es un valor de $_FILES.
     * Si es otra cosa, lanza una Exception.
     *
     * @param string $path El path donde guardar el archivo. Debe incluir el nombre también.
     * @throws \Exception
     */
    public function save(string $path)
    {
        // Preguntamos si es un base64 o un array de $_FILES.
        if(is_array($this->file)) {
            $this->uploadFile($path);
        } else if(is_string($this->file)) {
            $this->uploadBase64($path);
        } else {
            throw new \Exception('El tipo de archivo provisto no es válido. Debe ser un array o un string, se pasó un ' . gettype($this->file) . '.');
        }
    }

    /**
     * Guarda el archivo subido por $_FILES.
     *
     * @param string $path
     * @return bool
     */
    private function uploadFile(string $path)
    {
        return move_uploaded_file($this->file['tmp_name'], $path);
    }

    /**
     * Guarda el archivo subido como un base64.
     *
     * @param string $path
     * @return false|int
     */
    private function uploadBase64(string $path)
    {
        // TODO: Tal vez verificar que haya una "," antes del explode, por si la quitaron esa parte
        // antes del envío al server.
        // Hacemos un explode del string de la imagen para obtener la parte que nos interesa.
        $imagenParts = explode(',', $this->file);

        // Teniendo separado el base64, vamos a decodificarlo a su archivo original (imagen, en este caso),
        // con ayuda de la función base64_decode de php.
        $imagenDecoded = base64_decode($imagenParts[1]);

        // Ahí tenemos la imagen ya decodificada en _memoria_.
        // El paso final sería grabar en disco la imagen.
        return file_put_contents($path, $imagenDecoded);
    }
}
