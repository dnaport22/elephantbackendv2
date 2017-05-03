# Elephant App Backend v2
# REST API - Documentation
This document will cover the basic usage of the elephant app backend v2 REST API.

-
# API Usage
> <h3>Base url: http://backendv2.myelephant.xyz</h3>

## Endpoint
The Elephant App API has only one endpoint which could also be extended when new Components are developed. The calls are defined by passing suitable parameters with the endpoint.
> <h3>/elephantapp/rest</h3>

## Parameters

 - __User__
   - POST [`/?entity=user&type=login`](#login)
   - POST [`/?entity=user&type=register`](#register)
   - POST [`/?entity=user&type=user_activate`](#activate)
   - POST [`/?entity=user&type=user_delete`](#delete)
   - POST [`/?entity=user&type=user_request_reset_pass`](#request-reset)
   - POST [`/?entity=user&type=user_reset_pass`](#reset-pass)
   
 - __Item__ 
   - POST [`/?entity=item&type=item_upload`](#upload-item)
   - POST [`/?entity=item&type=item_delete`](#delete-item)
   - POST [`/?entity=item&type=item_donate`](#donate-item)
   - POST [`/?entity=item&type=item_request`](#request-item)
   - GET  [`/?entity=item&type=item_all`](#get-all-items)
   - GET  [`/?entity=item&type=item_user_only`](#get-user-items)

#User
## <a name='login'>Login user</a>
### `/?entity=user&type=login`
__Type:__ application/json <br/>
__Data:__ <br/>

 - email (string) <br/>
 - pass (string)
 
```
Sample:

{
	"email": "youremail@lsbu.ac.uk",
	"pass": "yourpassword"
}
```

__Response:__ <br/>

<span style="color:green">on-Login-Success:</a>

```
{
	"status": 1,
	"message": "success login",
	"body": {
		"username": "name",
		"email": "email@lsbu.ac.uk",
		"uid": 1
	}
}

```

<span style="color:red">on-Invalid-Password:</a>

```
{
	"status": 0,
	"message": "invalid password",
}
```

<span style="color:red">on-System-Error:</a>

```
{
	"status": 0,
	"message": "error while logging in",
}
```

## <a name='register'>Register user</a>
### `/?entity=user&type=register`
__Type:__ application/json <br/>
__Data:__ <br/>

 - name (string) <br/>
 - email (string) <br/>
 - pass (string)

```
Sample:

{
	"name": "myname",
	"email": "mymail@lsbu.ac.uk",
	"pass": "mypassword"
}
```

__Response:__ <br/>

<span style="color:green">on-Register-Success:</a>

```
{
	"status": 1,
	"message": "success register",
}
```
<span style="color:red">on-Name-Exist:</a>

```
{
	"status": 0,
	"message": "username exists",
}
```
<span style="color:red">on-Email-Exist:</a>

```
{
	"status": 0,
	"message": "email exists",
}
```

## <a name='activate'>Activate account</a>
### `/?entity=user&type=user_activate`
__Type:__ application/json <br/>
__Data:__ <br/>

- uid (int)
- code (string)

```
Sample:

{
	"uid": "1",
	"code": "sdkjfbskjdbfa76148b423bik98i"
}
```

__Response:__ <br/>

<span style="color:green">on-Activate-Success:</a>

```
{
	"status": 1,
	"message": "account activated successfully",
}
```
<span style="color:green">on-Account-Already-Active:</a>

```
{
	"status": 1,
	"message": "account already active",
}
```
<span style="color:red">on-Account-Activate-Error:</a>

```
{
	"status": 0,
	"message": "error while activating account",
}
```

## <a name='request-reset'>Request password reset</a>
### `/?entity=user&type=user_request_reset_pass`
__Type:__ application/json <br/>
__Data:__ <br/>

- email (string)

```
Sample:

{
	"email": "mymail@lsbu.ac.uk"
}
```

__Response:__ <br/>

<span style="color:green">on-Reset-Link-Sent:</a>

```
{
	"status": 1,
	"message": "password reset email sent",
}
```

<span style="color:red">on-Reset-Link-Error:</a>

```
{
	"status": 0,
	"message": "error while sending pass reset email",
}
```



## <a name='reset-pass'>Reset password</a>
### `/?entity=user&type=user_reset_pass`
__Type:__ application/json <br/>
__Data:__ <br/>

- uid (int) <br/>
- code (string) <br/>
- pass (string) <br/>

```
{
	"uid": 1,
	"code": "wadbkabsdldjnkad70o9odas",
	"pass": "newpassword"
}
```

__Response:__ <br/>

<span style="color:green">on-Reset-Success:</a>

```
{
	"status": 1,
	"message": "password reset success",
}
```

<span style="color:red">on-Reset-Error:</a>

```
{
	"status": 0,
	"message": "error while reseting password",
}
```

#Item
## <a name='upload-item'>Upload item</a>
### `/?entity=item&type=item_upload`
__Type:__ form-data <br/>
__Data:__ <br/>

 - name (string)
 - desc (string)
 - image (file)
 - uid (int)

__Response:__ <br/>

<span style="color:green">on-Success:</a>

```
{
	"status": 1,
	"message": "item uploaded",
}
```

<span style="color:red">on-Error:</a>

```
{
	"status": 0,
	"message": "system error",
}
```

## <a name='request-item'>Request item</a>
### `/?entity=item&type=item_request`
__Type:__ application/json <br/>
__Data:__ <br/>

 - msg (string)
 - nid (int)
 - uid (int)

__Response:__ <br/>

<span style="color:green">on-Success:</a>

```
{
	"status": 1,
	"message": "item requested",
}
```

<span style="color:red">on-Error:</a>

```
{
	"status": 0,
	"message": "system error",
}
```

## <a name='delete-item'>Delete item</a>
### `/?entity=item&type=item_delete`
__Type:__ application/json <br/>
__Data:__ <br/>

 - nid (string)

__Response:__ <br/>

<span style="color:green">on-Success:</a>

```
{
	"status": 1,
	"message": "item deleted",
}
```

<span style="color:red">on-Error:</a>

```
{
	"status": 0,
	"message": "system error",
}
```

## <a name='donate-item'>Donate item</a>
### `/?entity=item&type=item_donate`
__Type:__ application/json <br/>
__Data:__ <br/>

 - nid (string)

__Response:__ <br/>

<span style="color:green">on-Success:</a>

```
{
	"status": 1,
	"message": "item donated",
}
```

<span style="color:red">on-Error:</a>

```
{
	"status": 0,
	"message": "system error",
}
```

## <a name='get-all-item'>Get all items</a>
### `/?entity=item&type=item_all`
__Type:__ application/json <br/>
__Data:__ <br/>

 - offset (int)

__Response:__ <br/>

<span style="color:green">on-Success:</a>

```
{
	"data": {
		"id": 1,
		"name": book,
		"description": foobar,
		"owner": 2,
		"image_src": "http://jbdsakjhbas",
		"post_date": 22/09/1889
	}
}
```

<span style="color:red">on-Error:</a>

```
{
	"status": 0,
	"message": "system error",
}
```

## <a name='get-user-item'>get user items</a>
### `/?entity=item&type=item_user`
__Type:__ application/json <br/>
__Data:__ <br/>

 - offset (int)

__Response:__ <br/>

<span style="color:green">on-Success:</a>

```
{
	"data": {
		"id": 1,
		"name": book,
		"description": foobar,
		"owner": 2,
		"image_src": "http://jbdsakjhbas",
		"post_date": 22/09/1889
	}
}
```

<span style="color:red">on-Error:</a>

```
{
	"status": 0,
	"message": "system error",
}
```