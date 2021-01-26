<?php

namespace Webid\LanguageTool\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Language extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'languages_flags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'flag',
    ];

    /**
     * Flags key for css class name
     * Why ? Exemple : EN flag is "GB" class name and not "EN"
     *
     * @var array
     */
    const FLAGS_BY_LOCAL = [
        // Inutile de mettre les langues dont le code pays et le code langue sont les mêmes, ex : fr => fr
        'en' => 'gb',
        'ar' => 'dz',
        'ja' => 'jp',
        'pt-br' => 'br',
        'zh' => 'cn',
    ];

    /**
     * Retourne le drapeau à utiliser pour une langue donnée
     *
     * @param string $local
     *
     * @return mixed
     */
    public static function flagsByLocal($local)
    {
        return Arr::get(static::FLAGS_BY_LOCAL, $local, $local);
    }

    /**
     * Override save
     *
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        if ($this->flag && !$this->name) {
            $search = null;
            foreach (config('translatable.locales') as $local => $language) {
                if ($local == self::getLocalByFlag($this->flag)) {
                    $search = $language;
                    break;
                }
            }
            $this->name = $search ? $search : null;
        }
        return parent::save($options);
    }

    /**
     * Return local of given flag
     *
     * @param String $flag
     *
     * @return mixed
     */
    public static function getLocalByFlag(String $flag)
    {
        $LocalsByFlag = array_flip(self::FLAGS_BY_LOCAL);
        return Arr::get($LocalsByFlag, $flag, $flag);
    }
}
