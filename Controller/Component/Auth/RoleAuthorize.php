<?php

App::uses('BaseAuthorize', 'Controller/Component/Auth');

class RoleAuthorize extends BaseAuthorize {

	public function authorize($user, CakeRequest $request) {
		return empty($request->params['prefix']) ||
			(!empty($user['role']) && $request->params['prefix'] === $user['role']);
	}

}
