# Postman API Testing Guide - UniFAST-TDP SMS
## Manual Testing of Logout & Forgot Password Endpoints

## Overview
This guide shows you **step-by-step** how to manually test the **Logout** and **Forgot Password** API endpoints in Postman without importing files.

> **✅ NOTE:** The API endpoints have been created and are now available at:
> - `POST /api/login` - User login
> - `POST /api/logout` - User logout  
> - `POST /api/forgot-password` - Request password reset
> - `POST /api/verify-code` - Verify reset code
> - `POST /api/reset-password` - Complete password reset

---

## Setup Before Testing

### 1. Create a New Request in Postman
1. Open Postman
2. Click **+ New** (top left)
3. Select **HTTP Request**
4. You now have a blank request to work with

### 2. Create Variables for Easy Testing
Click the **Environment** button (top right) and add these variables:

| Variable | Value | Example |
|----------|-------|---------|
| `base_url` | Your API base URL | `http://localhost/delossantos-sms` |
| `auth_token` | Bearer token from login | Get from login response |
| `reset_code` | Code sent to user email | From forgot password response |
| `reset_token` | Token from code verification | From verify code response |

✅ **Tip:** After each request, copy important values from the response and save them to these variables for the next request.

---

## Test 1: Login & Get Token

### Step-by-Step in Postman

1. **Create a new request:**
   - Method: **POST**
   - URL: `http://localhost/delossantos-sms/api/login`

2. **Go to Headers tab** and add:
   - Key: `Content-Type` → Value: `application/json`
   - Key: `Accept` → Value: `application/json`

3. **Go to Body tab:**
   - Select **raw** (not form-data)
   - Select **JSON** from dropdown
   - Copy and paste:
   ```json
   {
     "email": "admin@example.com",
     "password": "password123"
   }
   ```

4. **Click Send**

5. **Copy the token from response:**
   - Find `"token": "eyJ..."` in the response
   - Copy that long token string
   - Save it to your `auth_token` variable (or paste it in the next requests)

✅ **Expected:** Status `200` with token in response

> **Test Credentials:** Use any user email/password from your database. Common test account: `admin@example.com` with password `password123`

---

## Test 2: Logout

### Step-by-Step in Postman

1. **Create a new request:**
   - Method: **POST**
   - URL: `http://localhost/delossantos-sms/api/logout`

2. **Go to Headers tab** and add:
   - Key: `Authorization` → Value: `Bearer YOUR_TOKEN_HERE` (paste the token from Test 1)
   - Key: `Accept` → Value: `application/json`

3. **Body:** Leave empty (no body needed)

4. **Click Send**

✅ **Expected:** Status `200` with message "logged out successfully"

❌ **Error:** If you see `401 Unauthorized`, your token is invalid or expired - run Test 1 again

---

## Test 3: Request Password Reset (Forgot Password)

### Step-by-Step in Postman

1. **Create a new request:**
   - Method: **POST**
   - URL: `http://localhost/delossantos-sms/api/forgot-password`

2. **Go to Headers tab** and add:
   - Key: `Content-Type` → Value: `application/json`
   - Key: `Accept` → Value: `application/json`

3. **Go to Body tab:**
   - Select **raw** → **JSON**
   - Copy and paste:
   ```json
   {
     "email": "student@example.com"
   }
   ```

4. **Click Send**

5. **Copy the reset code from response:**
   - Find `"code": "123456"` in the response JSON
   - Note this code for the next test

✅ **Expected:** Status `200` with reset code in response

> **Development Mode:** In testing/development mode, the reset code is returned directly in the response. In production, you would receive it via email only. Remove the code from the response before production deployment.

---

## Test 4: Verify Reset Code

### Step-by-Step in Postman

1. **Create a new request:**
   - Method: **POST**
   - URL: `http://localhost/delossantos-sms/api/verify-code`

2. **Go to Headers tab** and add:
   - Key: `Content-Type` → Value: `application/json`
   - Key: `Accept` → Value: `application/json`

3. **Go to Body tab:**
   - Select **raw** → **JSON**
   - Copy and paste (use the code from Test 3):
   ```json
   {
     "email": "student@example.com",
     "code": "123456"
   }
   ```

4. **Click Send**

5. **Copy the reset token from response:**
   - Find `"token": "eyJ..."` in the response
   - Copy this token for the next test

✅ **Expected:** Status `200` with reset token in response

---

## Test 5: Reset Password (Final Step)

### Step-by-Step in Postman

1. **Create a new request:**
   - Method: **POST**
   - URL: `http://localhost/delossantos-sms/api/reset-password`

2. **Go to Headers tab** and add:
   - Key: `Content-Type` → Value: `application/json`
   - Key: `Accept` → Value: `application/json`

3. **Go to Body tab:**
   - Select **raw** → **JSON**
   - Copy and paste (use the token from Test 4):
   ```json
   {
     "email": "student@example.com",
     "password": "newpassword123",
     "password_confirmation": "newpassword123",
     "token": "eyJ0eXAiOiJKV1QiLC..."
   }
   ```

4. **Click Send**

✅ **Expected:** Status `200` with message "Password reset successfully"

---

## API Endpoints Testing

---

## Common Issues & Solutions

| Problem | Solution |
|---------|----------|
| **401 Unauthorized on Logout** | Your token is invalid. Run Test 1 (Login) again to get a fresh token. |
| **"Invalid credentials" on Login** | Check email and password are correct. Verify user exists in database. |
| **"Code not found" on Verify** | Use the exact code from the forgot password response. Codes expire after 60 minutes. |
| **"Token mismatch" on Reset Password** | Make sure you're using the token from Test 4 (Verify Code), not a different token. |
| **"Method Not Allowed (405)" on other endpoints** | The API only supports POST for these endpoints. Don't try GET, PUT, or DELETE. |

---

## Testing Notes

✅ **Do's:**
- Always test Login first before testing Logout
- Copy values from responses carefully (tokens, codes)
- Use test email addresses (not production users)
- Keep strong passwords when testing
- Check the response status code (200 = success)

❌ **Don'ts:**
- Don't use GET, PUT, or DELETE methods (API only supports POST)
- Don't hardcode tokens in your requests (copy from responses)
- Don't test with production emails
- Don't skip reading error messages in responses

---

## Response Status Codes

| Code | Meaning | What to Do |
|------|---------|-----------|
| **200** | Success | Request worked! Check response body for tokens/codes |
| **400** | Bad Request | JSON format error - check your body syntax |
| **401** | Unauthorized | Token invalid/missing - run Login test again |
| **404** | Not Found | Wrong endpoint URL - double check the URL |
| **405** | Method Not Allowed | Wrong HTTP method - must use POST |
| **422** | Validation Error | Missing required fields - check request body |
| **500** | Server Error | Server problem - check application logs |

---

## Troubleshooting

### My token keeps expiring
- Tokens have a time limit (usually 1 hour)
- Before they expire, run Test 1 (Login) to get a fresh token
- Copy the new token to use in Test 2

### I'm getting "CORS error" 
- Make sure your URL base is correct: `http://localhost/delossantos-sms`
- Check headers include `Accept: application/json`

### Password reset code not working
- Codes expire after 60 minutes - request a new one if it's old
- Copy the exact code from the response (case matters)
- Use the same email address in all 3 steps (request, verify, reset)

---

## Quick Reference - Copy & Paste

### Login Request
```
POST: http://localhost/delossantos-sms/api/login
Headers: Content-Type: application/json, Accept: application/json
Body:
{
  "email": "admin@example.com",
  "password": "password123"
}
```

### Logout Request
```
POST: http://localhost/delossantos-sms/api/logout
Headers: Authorization: Bearer TOKEN_HERE, Accept: application/json
Body: (empty)
```

### Forgot Password Request
```
POST: http://localhost/delossantos-sms/api/forgot-password
Headers: Content-Type: application/json, Accept: application/json
Body:
{
  "email": "student@example.com"
}
```

### Verify Code Request
```
POST: http://localhost/delossantos-sms/api/verify-code
Headers: Content-Type: application/json, Accept: application/json
Body:
{
  "email": "student@example.com",
  "code": "123456"
}
```

### Reset Password Request
```
POST: http://localhost/delossantos-sms/api/reset-password
Headers: Content-Type: application/json, Accept: application/json
Body:
{
  "email": "student@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123",
  "token": "TOKEN_FROM_VERIFY"
}
```

