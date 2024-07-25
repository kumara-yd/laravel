<?php
namespace App\Traits;

use App\Models\Navigation;
use App\Models\Preference;

trait DisplayTrait
{
    public function display(string $view, array $data = [])
    {
        $sidebar = $this->getSidebar();
        $lang = ['en', 'id'];
        $site = $this->getPreference();
        $data = array_merge($data, ['sidebar' => $sidebar], ['_langs'=>$lang], ['_site'=>$site]);

        return view($view, $data);
    }

    protected function getSidebar()
    {
        return Navigation::where('display', '1')
                        ->where('parent_id', null)
                        ->where('active', '1')
                        ->with(['child' => function ($query) {
                            $query->where('active', '1');
                            $query->where('display', '1');
                            $query->orderBy('order', 'asc');
                        }])
                        ->orderBy('order', 'asc')->get();
    }

    protected function getPreference(){
        $preferences = Preference::where('group', 'site')->get();
        $new = new \stdClass();

        foreach ($preferences as $preference) {
            $new->{$preference['name']} = $preference['value'];
        }

        // Output
        return $new;
    }

    protected function setRule($role=null) {
        auth()->user()->hasRole('admin') || auth()->user()->can($role) || abort(403);
    }
}
