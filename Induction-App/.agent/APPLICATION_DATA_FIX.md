# Application View Data Fix

## Issue
The Application Details page was showing "Profile information not completed" and "Contact details not added" even though data existed in the database.
Also, only Name and Email were showing, but other details were missing.

## Root Cause
**Mismatch in Data Property Names**

1. **Controller Loading:**
   The controller loads relationships using camelCase names (standard Laravel):
   ```php
   $appliedPost->load([
       'user.candidateProfile',
       'user.userContact',
       'user.qualifications.degreeType'
   ]);
   ```

2. **JSON Serialization:**
   When Laravel converts these models to JSON for Inertia, it converts the relationship keys to **snake_case**:
   - `candidateProfile` → `candidate_profile`
   - `userContact` → `user_contact`
   - `degreeType` → `degree_type`

3. **Frontend Access (The Bug):**
   The Vue component was trying to access them using **camelCase**:
   ```javascript
   // ❌ WRONG
   props.application?.user?.candidateProfile
   props.application?.user?.userContact
   q.degreeType?.name
   ```
   Since the keys in the JSON were snake_case, these returned `undefined`.

## Solution Applied

Updated `ApplicationDetail.vue` to access the properties using **snake_case**, matching the actual JSON structure.

### 1. Fixed Profile Data Access
**Before:**
```javascript
return props.application?.user?.candidateProfile || null
```

**After:**
```javascript
return props.application?.user?.candidate_profile || null
```

### 2. Fixed Contact Data Access
**Before:**
```javascript
return props.application?.user?.userContact || null
```

**After:**
```javascript
return props.application?.user?.user_contact || null
```

### 3. Fixed Qualification Degree Type Access
**Before:**
```vue
{{ q.degreeType?.name || 'Unknown' }}
```

**After:**
```vue
{{ q.degree_type?.name || 'Unknown' }}
```

## Verification
This explains exactly why:
1. **Name and Email showed:** They are direct properties of `user` object (`name`, `email`), which are simple attributes.
2. **Profile/Contact didn't show:** They are relationships, and the key names didn't match.
3. **Edit Profile worked:** The `Profile.vue` component was already correctly using `candidate_profile` and `user_contact`.

## Result
The Application Details page will now correctly display:
- ✅ All Personal Information (Father's Name, CNIC, etc.)
- ✅ All Contact Information (Phone, Address, etc.)
- ✅ Qualification Degree Titles
- ✅ Profile Photo (which is inside `candidate_profile`)

No changes were needed in Migration or Models as they were already correct. The issue was purely in how the frontend was trying to read the data.
