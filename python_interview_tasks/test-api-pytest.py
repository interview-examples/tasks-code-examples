from django.urls import path
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt


@csrf_exempt
def claims_view(request):
    return JsonResponse([], safe=False)

urlpatterns = [
    path("claims/", claims_view),
]
import os
import django
from django.conf import settings

# Minimal Django settings for the test.
if not settings.configured:
    settings.configure(
        SECRET_KEY='dummy',
        INSTALLED_APPS=[
            'django.contrib.contenttypes',
            'django.contrib.auth',
            'rest_framework',
        ],
        DATABASES={
            'default': {
                'ENGINE': 'django.db.backends.sqlite3',
                'NAME': ':memory:',
            }
        },
        MIDDLEWARE=[],
        ROOT_URLCONF=__name__,  # если нужны урлы
        REST_FRAMEWORK={},
    )
    django.setup()

import pytest
from rest_framework.test import APIClient

@pytest.mark.django_db
def test_get_claims():
    client = APIClient()
    response = client.get("/claims/")
    assert response.status_code == 200
    assert isinstance(response.json(), list)
