<?php

namespace Dontdrinkandroot\ActivityPubOrmBundle\Tests\TestApp\DataFixtures\LocalActor;

use Dontdrinkandroot\ActivityPubCoreBundle\Model\Type\Extended\Actor\ActorType;

class Person extends AbstractLocalActorFixture
{
    public const USERNAME = 'person';

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
        return ActorType::PERSON->value;
    }

    /**
     * {@inheritdoc}
     */
    protected function getPrivateKeyPem(): string
    {
        return <<<PEM
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAzsMzNK4bE45JO678FC0OzhGuqvWCdbVkAEhzYOLO5RmYBFqW
QIPKDbIf2Fazt99TytufdA34SumjN1rlz4AqUXzG6eQYUJ3hh/xSRc8wuaRLZqu3
n3cBLtEw17anitng58HodvfsCUDf6TNFzlPj7o5G/CwXqD9eyC6uU6sAQHqd4A6P
MAka6W1QU3LV6UqWfJ3BmnQCu7QcTKeqXuaV0wCp1bIKMPH7WN44KX/H/5jkrliF
dAmGrV06CkADJgUjN4ZGTwhTrblOcZ6Ro88GwySglr59CZJWduvRP+RwS8V56xeg
uYQtiATu1++Z1MLepP/1xrI6KNokIqXUTLpFUQIDAQABAoIBAAXxA4ltwF7vPYj+
xgUhZ1XCGdIVVH6j6//7FP+xfM8GDYGAheVMNDPxDKu3kBoGS57ecUZROXOTo6pN
TSHJlc26J3AkqxMz+j1hYY7afZS0FSuZ3yCwt4K8JappOAbMLIOUZaT3iluYtuZ5
X/XmILxj78PC05o02fkoKD/Ev/DaV+/PVmWYoF/BLJ/FjpbQXF/NFyQFFXUO/i/A
Gr7e4RVL+GV5FOL/ad3lbC3i4F6VeVWuaB5+qeUZuSEK89cijz0I5IGde6H7wi+E
OEK1QCHTxgUDwsXqLXO+cngoSwRtd84OAY1Yj4n9ZevILHvq9BpWi3VcxF8EZ21n
elMm5q0CgYEA75c2gwjGOYQmmORKYIeq0FHDX5Q+sJHJEEsH+d2ifPIRNxuhnBG9
QnTbd6KN5T2+lPX82qAZQyiMPIoVrUaIA1kRFqRHsbnpRGPmPmSYd3yok+626T1h
py42+1/UJGxd6zcVEz7LZrYg4DVcd8viH/5UItzAzequYEKy2sdfb90CgYEA3Oxn
mddCnNUcQNaLz+XT8pigJH+iyTCNQJSHuz9JVkNydqSFfU1H+blA91XDQlvsMq1H
0FwBVq8KzKnrngI729rFCuU9dJathdG3rQIKb7pQb1/GoQ+5AwfhBPZsXmXYsSAs
EPubZXMM86lTGvQfTlzoALyT+DetN4w51XJLDgUCgYA5Ya3clC1leREFbSejFtsC
KZLxQUACaegNzuqKHVrdMdyNpkB+cIEzeWlWrcfuL2uFoaR9d/qU6xErLqciaNIK
ezpsgcvp9Oy5RHPQXadmdqSpSXLlSZ4pvBfO/JSCZLHZs8eIZHGyl8wn5p/O0TXH
E9JyxwwmRR6eT1smqrlgwQKBgQC+rdiZgp6+6H1TRRo1XUO7DpqiBfwFtD8mb0xb
hDsTFnHUDxocVTh7RLbbA43dV6Oc9cyW/OI25CvpC/wOTBVIJCGPzt5lI6wvZRwo
WiuR1XiZOEwjNYPVJtbDxsEwFK2b6429Nr0gKdYS9KGDERN4Ol4QTLNWOQ/rcr90
CArZ1QKBgF7I2lfU2tPLZpbY3Nv7Ww6QcBQvZZ08q0FwF9emOJtJNyUH85XoXrzX
81aJpZyApTh2eDZ5JYykz7Gi1hEpPmS5uoIZGKyRrGiwqm6IfwUN2N1JUb3WwBic
krjA/89F2dBnRAfRVN7/Y67WxpQIgG9GxZDsm2chMpznXk8PYBxM
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
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzsMzNK4bE45JO678FC0O
zhGuqvWCdbVkAEhzYOLO5RmYBFqWQIPKDbIf2Fazt99TytufdA34SumjN1rlz4Aq
UXzG6eQYUJ3hh/xSRc8wuaRLZqu3n3cBLtEw17anitng58HodvfsCUDf6TNFzlPj
7o5G/CwXqD9eyC6uU6sAQHqd4A6PMAka6W1QU3LV6UqWfJ3BmnQCu7QcTKeqXuaV
0wCp1bIKMPH7WN44KX/H/5jkrliFdAmGrV06CkADJgUjN4ZGTwhTrblOcZ6Ro88G
wySglr59CZJWduvRP+RwS8V56xeguYQtiATu1++Z1MLepP/1xrI6KNokIqXUTLpF
UQIDAQAB
-----END PUBLIC KEY-----
PEM;
    }
}
