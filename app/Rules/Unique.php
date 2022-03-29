<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Unique implements Rule
{
    protected $table;
    protected $column;
    protected $organizationId;
    protected $ignoreId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct( $table, $column=null )
    {
        if( is_subclass_of($table, Model::class) )
        {
            $table = ( new $table )->getTable();
        }

        $this->table = $table;
        
        $this->column = $column;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {        
        $column = $this->column ? $this->column : $attribute;

        $query = DB::table( $this->table )->where( $column, $value);

        if( $this->organizationId )
        {
            $query->where('organization_id', $this->organizationId );
        }

        if( $this->ignoreId )
        {
            $query->where('id', '!=', $this->ignoreId);
        }

        return $query->count() < 1 ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute already exists.';
    }

    /**
     * Set organization id
     */
    public function forOrganization( $idOrModel )
    {
        $this->organizationId = is_a($idOrModel, Model::class ) ? $idOrModel->id : $idOrModel;

        return $this;
    }

    /**
     * Set ignore
     */
    public function ignore( $idOrModel )
    {
        $this->ignoreId = is_a($idOrModel, Model::class ) ? $idOrModel->id : $idOrModel;

        return $this;
    }
} 
