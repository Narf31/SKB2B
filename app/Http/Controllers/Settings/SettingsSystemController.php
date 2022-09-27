<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings\SettingsSystem;
use Illuminate\Http\Request;
use App\Models\Settings\Integrations;
use Validator;
use App\Models\Settings\IntegrationsVersions;
use App\Models\Settings\IntegrationsVersionsMainFormValues;
use App\Models\Settings\IntegrationsVersionsSupplierFormValues;

class SettingsSystemController extends Controller {

    public function __construct() {
        $this->middleware('permissions:settings,system');
    }

    public function index() {


        return view('settings.system.index', [

        ]);
    }

    public function save(Request $request) {
        $base = $request->get('base');
        $front = $request->get('front');

        $arr = $_POST;
        foreach ($_POST as $key => $myPost) {
            if ($key != '_token') {
                $data = $request->get($key);
                if ($data) {
                    $this->seveData($key, $data);
                }
            }
        }



        return redirect("/settings/system");
    }

    public function seveData($types, $data) {

        SettingsSystem::where('types', $types)->delete();

        foreach ($data as $k => $d) {
            SettingsSystem::create([
                'types' => $types,
                'param' => $k,
                'val' => $d,
            ]);
        }
    }

    public function addIntegration(Request $request) {

        $errors = [];

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                        'title' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->getMessages();
            }


            if (!$errors) {
                $integration = new Integrations;
                $integration->fill($request->all());
                $integration->save();
                return parentReload();
            }
        }


        return view('settings.system.integration.create', [
            'messages' => $errors,
        ]);
    }

    public function editIntegration($id, Request $request) {
        $errors = [];
        $integration = Integrations::findOrFail($id);
        if ($integration) {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                            'title' => 'required',
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors()->getMessages();
                }


                if (!$errors) {
                    $integration->fill($request->all());
                    $integration->save();
                    return parentReload();
                }
            }

            return view('settings.system.integration.edit', [
                'messages' => $errors,
                'integration' => $integration,
            ]);
        }
    }

    public function deleteIntegration($id) {
        $integration = Integrations::findOrFail($id);
        $integration->delete();
        return parentReload();
    }

    public function addVersion($integration_id, Request $request) {

        $errors = [];

        $integration = Integrations::findOrFail($integration_id);


        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                        'title' => 'required',
                        'integration_class' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->getMessages();
            }

            if (!$errors) {
                $integration = new IntegrationsVersions;
                $integration->fill($request->all());
                $integration->save();
                return parentReload();
            }
        }


        return view('settings.system.integration.version.create', [
            'messages' => $errors,
            'integration' => $integration,
        ]);
    }

    public function editVersion($integration_id, $id, Request $request) {
        $errors = [];
        $integration = Integrations::findOrFail($integration_id);
        $version = IntegrationsVersions::findOrFail($id);

        if ($integration && $version) {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                            'title' => 'required',
                            'integration_class' => 'required',
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors()->getMessages();
                }

                $version->fill($request->all());
                if (!$errors) {
                    $version->fill($request->all());
                    $version->save();
                    return parentReload();
                }
            }

            return view('settings.system.integration.version.edit', [
                'messages' => $errors,
                'integration' => $integration,
                'version' => $version,
            ]);
        }
    }

    public function deleteVersion($integration_id, $id) {
        $version = IntegrationsVersions::findOrFail($id);
        $version->delete();
        return parentReload();
    }

    public function versionMainForm($integration_id, $id, Request $request) {
        $errors = [];
        $integration = Integrations::findOrFail($integration_id);
        $version = IntegrationsVersions::findOrFail($id);
        $formValues = IntegrationsVersionsMainFormValues::where('version_id', '=', $version->id);

        if (class_exists($version->integration_class)) {
            $api = new $version->integration_class;

            $form = $api->getMainForm();

            if ($request->isMethod('post')) {
                $post = $request->all();
                /* Validate */

                if ($formValues->count()) {
                    $formValues->delete();
                }

                foreach ($form as $field) {
                    if (isset($post[$field['name']])) {
                        $integrationsVersionsMainFormValues = new IntegrationsVersionsMainFormValues;
                        $integrationsVersionsMainFormValues->form_key = $field['name'];
                        $integrationsVersionsMainFormValues->value = $post[$field['name']] ?? '';
                        $integrationsVersionsMainFormValues->version_id = $version->id;
                        $integrationsVersionsMainFormValues->save();
                    }
                }
                return parentReload();
            }

            return view('settings.system.integration.version.main_form', [
                'messages' => $errors,
                'integration' => $integration,
                'version' => $version,
                'form' => $api->getMainForm(),
                'formValues' => $formValues->get()->keyBy('form_key'),
            ]);
        }
    }

    public function VersionSupplierForm($integration_id, $id, Request $request) {
        $errors = [];
        $integration = Integrations::findOrFail($integration_id);
        $version = IntegrationsVersions::findOrFail($id);
        $formValues = IntegrationsVersionsSupplierFormValues::where('version_id', '=', $version->id);

        if (class_exists($version->integration_class)) {
            $api = new $version->integration_class;

            $form = $api->getSupplierForm();

            if ($request->isMethod('post')) {
                $post = $request->all();
                /* Validate */

                if ($formValues->count()) {
                    $formValues->delete();
                }

                foreach ($form as $field) {
                    if (isset($post[$field['name']])) {
                        $integrationsVersionsMainFormValues = new IntegrationsVersionsSupplierFormValues;
                        $integrationsVersionsMainFormValues->form_key = $field['name'];
                        $integrationsVersionsMainFormValues->value = $post[$field['name']] ?? '';
                        $integrationsVersionsMainFormValues->version_id = $version->id;
                        $integrationsVersionsMainFormValues->save();
                    }
                }
                return parentReload();
            }

            return view('settings.system.integration.version.supplier_form', [
                'messages' => $errors,
                'integration' => $integration,
                'version' => $version,
                'form' => $api->getMainForm(),
                'formValues' => $formValues->get()->keyBy('form_key'),
            ]);
        }
    }

}
