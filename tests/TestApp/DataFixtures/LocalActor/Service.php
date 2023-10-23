<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;

class Service extends AbstractLocalActorFixture
{
    const USERNAME = 'service';

    /**
     * {@inheritdoc}
     */
    protected function getUsername(): string
    {
        return self::USERNAME;
    }

    /**
     * {@inheritdoc}
     */
    protected function getType(): string
    {
        return ActorType::SERVICE->value;
    }

    /**
     * {@inheritdoc}
     */
    protected function getPrivateKeyPem(): string
    {
        return <<<PEM
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAi29XYOkLqoYfXA4ZxjQmz7KaUXHRXwToGkWiYmTT5j1IYEW7
UZKGrP5hbccWcmCeGlafRnM03oaR0csvlcSGyRTMUHVQSt5zBMVb+dZKw688KEGh
n1gx3bt/jOcqLWr80D2HeFXlrgK+UzCcZMOO83J25/pCcfkVoqPnS38LeyMTBoij
B7SPMoQwU3s942sCMsOy4li4mxZ3Yl3IGfJFfqq7UviKP4R/lpyI1mFNcLJbrcrZ
xnXiu4yNW0GGPTtS8KotxKqn4oUFie5X42AnXvl1HEYz3MxDR2l5dC7A3fQWa1Wm
Qro/7M4jREA7XhbzsT7DhNlkj6LblXovbVlC3QIDAQABAoIBAAd/+o+3lhonsua4
Y9hRtGeGLR9fXP2Pt02E3FcPIrsaJcGQtQj0tcv26aLf5NjlhVHxjqMMwSKNBql/
R9tC7ssNetXpd+NApttYieBDQtJwVqIfSzdVpTNoVlQfMcx8M9M4BzuvE+nk5nuo
1c8Xs7s4t3IDEVetdOWPWMdEGW5+LQ19qFUwVp0nId2/n6InpJIO3PynaLx2bsy4
rYfoTg64ChpoPvNmPyWTBdXR2WlY1zc7RpkKsxXrm2tOF09G4hbKYlYW4kArf/5G
z+rvrsxFlz89Q5RusvTPUDm9+D2XO17x2z2iwmqRZ/zQgceN4TGWYCk1TmM4ExDV
StjQSqMCgYEAvBYM7APcUxwn0t/OZ5NCVJ8OgxL6uMSCWPBCSZaa+EG+tX5E/i6H
3A/FRe6Pi0A90iT+6u9w4F+5PHNRepoOgRjXdJF+CFm7qZmMe0AiJakwl4ioJHjF
HwtKLp3WWLuOKLj+5t5KilnEVXUZkqukvwkqh2EcDUJuPeXukaIVeGcCgYEAvcgp
qi/Ewd11RAkYg+N/CdBujvy8BhuHOVdMBhIIkx7VueiLn2FqfaVB1z2B24UmiV6N
kCHZekGaJwCQJZ+4AnVKntjnVOHYuKv6qpVBUrG50vqYPeBZ409iQSrR1QT8/DNx
wb7wHaWkdo1ih26EtqyZLjvQ8wQVy9f/t/0D8BsCgYAmZBXVIuCY8jlKuLYHvC4g
2ap7pKcaibnVb40IOj59h+XmY9SvUU4X4/wvTwdrs/wqZbTGvYL7uW404ZDzBnkJ
bsmjmILyL2a3sojTK38M0uEBPTqc3y3VLVfB9iOnTvkwZLpa42qxnKsPimxi3Lgu
6i8NHQw9xJ598e3lOgFJ5wKBgQCxALL7a7oTJj1syx72Q4QE30V+TvH+sEYakPTy
5Hbi4GtuDRnL+MudjDgwS8mFuFYM4QcfWrK/d9gScFABB0pT4JlMNfjsDghXlO8h
kjtuqRwrTlYXv9uWSj/Vj95M024wurpqfW7t98PAXnV64vUcezYTDO8A+NprWHXE
YFL/6QKBgHsKMabyg6jpPW/WvcQY5xb95yg5kTHgxrEzd95uNs3SEXOgLicONWIX
kagRBP3ll1BMjR+kWmT6vj66PFLU8J2F4AHnThbgBcGngHdXvm2k4r60ncoTQOSB
E26Mchas57ME3J/HF8f1fXkKSee7uwI+lH7xdx2n00FZMAVT1ucU
-----END RSA PRIVATE KEY-----
PEM;
    }

    /**
     * {@inheritdoc}
     */
    protected function getPublicKeyPem(): string
    {
        return <<<PEM
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi29XYOkLqoYfXA4ZxjQm
z7KaUXHRXwToGkWiYmTT5j1IYEW7UZKGrP5hbccWcmCeGlafRnM03oaR0csvlcSG
yRTMUHVQSt5zBMVb+dZKw688KEGhn1gx3bt/jOcqLWr80D2HeFXlrgK+UzCcZMOO
83J25/pCcfkVoqPnS38LeyMTBoijB7SPMoQwU3s942sCMsOy4li4mxZ3Yl3IGfJF
fqq7UviKP4R/lpyI1mFNcLJbrcrZxnXiu4yNW0GGPTtS8KotxKqn4oUFie5X42An
Xvl1HEYz3MxDR2l5dC7A3fQWa1WmQro/7M4jREA7XhbzsT7DhNlkj6LblXovbVlC
3QIDAQAB
-----END PUBLIC KEY-----
PEM;
    }
}
