# Jms Generator Yaml Reference

## Example

```php
class Document
{
    /**
     * @var string
     */
    private $tipoDoc;

    /**
     * @var string
     */
    private $nroDoc;

    /**
     * @return string
     */
    public function getTipoDoc()
    {
        return $this->tipoDoc;
    }

    /**
     * @param string $tipoDoc
     * @return Document
     */
    public function setTipoDoc($tipoDoc)
    {
        $this->tipoDoc = $tipoDoc;
        return $this;
    }

    /**
     * @return string
     */
    public function getNroDoc()
    {
        return $this->nroDoc;
    }

    /**
     * @param string $nroDoc
     * @return Document
     */
    public function setNroDoc($nroDoc)
    {
        $this->nroDoc = $nroDoc;
        return $this;
    }
}
```

## Result
```php
[
     'Document' => [
        'properties' => [
            'tipoDoc' => [
                'type' => 'string'
            ],
            'nroDoc' => [
                'type' => 'string'
            ]
        ]
     ]
]
```
