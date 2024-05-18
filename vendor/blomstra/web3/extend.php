<?php

/*
 * This file is part of blomstra/web3.
 *
 * Copyright (c) 2022 Blomstra Ltd.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blomstra\Web3;

use Fig\Http\Message\StatusCodeInterface;
use Flarum\Api\Serializer\CurrentUserSerializer;
use Flarum\Extend;
use Flarum\Frontend\Document;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less')
        ->content(function (Document $document) {
            $document->payload['ffiEnabled'] = ffiIsEnabled();
        }),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Policy())
        ->modelPolicy(Web3Account::class, Access\Web3AccountPolicy::class),

    (new Extend\Routes('api'))
        ->get('/web3/accounts', 'web3-accounts.index', Api\Controller\ListWeb3AccountsController::class)
        ->post('/web3/accounts', 'web3-accounts.create', Api\Controller\CreateWeb3AccountController::class)
        ->delete('/web3/accounts/{id}', 'web3-accounts.delete', Api\Controller\DeleteWeb3AccountController::class)
        ->post('/web3/token', 'web3-accounts.token', Api\Controller\CreateTokenWithWeb3Account::class)
        ->put('/web3/set-email', 'web3.set-email', Api\Controller\SetUserEmailController::class),

    (new Extend\Routes('forum'))
        ->post('/web3/login', 'web3-accounts.login', Forum\Controller\LoginWithWeb3AccountController::class)
        ->post('/web3/register', 'web3-accounts.register', Forum\Controller\RegisterWithWeb3AccountController::class),

    (new Extend\ModelVisibility(Web3Account::class))
        ->scope(Access\ScopeAccountVisiblity::class),

    (new Extend\ServiceProvider())
        ->register(Web3ServiceProvider::class),

    (new Extend\ErrorHandling())
        ->status('invalid_crypto_signature', StatusCodeInterface::STATUS_UNAUTHORIZED),

    (new Extend\ApiSerializer(CurrentUserSerializer::class))
        ->attribute('isEmailFake', function (CurrentUserSerializer $serializer, $user): bool {
            return str_contains($user->email, '@users.noreply');
        }),

    (new Extend\Settings())
        ->default('blomstra-web3.allow-sign-up', 1)
        ->default('blomstra-web3.signup-with-email', 1)
        ->default('blomstra-web3.no-email-signup-message', '')
        ->serializeToForum('blomstra-web3.allow-sign-up', 'blomstra-web3.allow-sign-up', 'boolval')
        ->serializeToForum('blomstra-web3.signup-with-email', 'blomstra-web3.signup-with-email', 'boolval')
        ->serializeToForum('blomstra-web3.prioritize-web3-auth-modals', 'blomstra-web3.prioritize-web3-auth-modals', 'boolval')
        ->serializeToForum('blomstra-web3.infura-project-id', 'blomstra-web3.infura-project-id')
        ->serializeToForum('blomstra-web3.no-email-signup-message', 'blomstra-web3.no-email-signup-message'),
];
