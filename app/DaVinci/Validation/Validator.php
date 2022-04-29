<?php
namespace DaVinci\Validation;

class Validator
{
    /** @var array Array  */
    protected $data = [];

    /** @var array Array */
    protected $rules = [];

    /** @var array Array */
    protected $errors = [];

    /**
     * Constructor.
     *
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;

        // Aplica la validación.
        $this->validate();
    }

    /**
     * Realiza la validación.
     */
    protected function validate()
    {
        foreach($this->rules as $name => $ruleList) {
            $this->applyRules($name, $ruleList);
        }
    }

    /**
     * @param string $name
     * @param array $ruleList
     * @throws Exception
     */
    protected function applyRules(string $name, array $ruleList)
    {
        foreach($ruleList as $ruleName) {
            $this->applyRule($ruleName, $name);
        }
    }

    /**
     * Parsea y aplica una regla de validación.
     *
     * @param string $ruleName
     * @param string $name
     * @throws Exception
     */
    protected function applyRule(string $ruleName, string $name): void
    {
        if(strpos($ruleName, ':') === false) {
            $method = "_" . $ruleName; // Ej: _required

            if(!method_exists($this, $method)) {
                throw new Exception('No existe una regla de validación llamada "' . $ruleName . '"');
            }

            $this->{$method}($name);
        } else {
            $ruleData = explode(':', $ruleName);

            $method = "_" . $ruleData[0];

            if(!method_exists($this, $method)) {
                throw new Exception('No existe una regla de validación llamada "' . $ruleName . '"');
            }

            $this->{$method}($name, $ruleData[1]);
        }
    }

    /**
     * Retorna true si la validación tuvo éxito.
     *
     * @return bool
     */
    public function passes(): bool
    {
        return count($this->errors) === 0;
    }

    /**
     * Retorna los errores de validación.
     *
     * @return array
     */
    public function getErrores(): array
    {
        return $this->errors;
    }

    /**
     * Agrega un error de validación.
     *
     * @param string $name
     * @param string $message+
     */
    protected function setError(string $name, string $message)
    {
        $this->errors[$name] = $this->errors[$name] ?? [];

        $this->errors[$name][] = $message;
    }

    /**
     * Valida que el campo no esté vacío.
     *
     * @param string $name
     * @return bool
     */
    protected function _required(string $name)
    {
        $value = $this->data[$name];

        if(empty($value)) {
            $this->setError($name, 'Debe ingresar un valor aquí.');
            return false;
        }
        return true;
    }

    /**
     * Valida que el dato sea un valor numérico.
     *
     * @param string $name
     * @return bool
     */
    protected function _numeric(string $name)
    {
        $value = $this->data[$name];
        if(!is_numeric($value)) {
            $this->setError($name, 'El ' . $name . ' debe ser un valor numérico.');
            return false;
        }
        return true;
    }

    /**
     * Valida que el dato tenga al menos $long caracteres.
     *
     * @param string $name
     * @param int $long
     * @return bool
     */
    protected function _min(string $name, int $long)
    {
        $value = $this->data[$name];
        if(strlen($value) < $long) {
            $this->setError($name, 'Este campo debe tener al menos ' . $long . ' caracter/es.');
            return false;
        }
        return true;
    }
}
