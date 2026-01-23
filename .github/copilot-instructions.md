# Copilot Instructions

**CAIS (Comprehensive Academic Information System)** is a dual-stack student portal: Laravel 12 REST API backend (`cais-test-app/`) and Expo/React Native mobile frontend (`React/`). Both stacks integrate via HTTP/Axios calls.

## Architecture Overview

### System Design: Two-Tier Full-Stack

```
┌─────────────────────────────────────────────────┐
│ React Native (Expo) - Mobile Client Layer       │
│ - File-based routing via expo-router            │
│ - NativeWind/Tailwind CSS styling               │
│ - Axios for HTTP requests                       │
│ - AsyncStorage for local data persistence       │
└────────────────────┬────────────────────────────┘
                     │ HTTP REST API
                     │ base: http://192.168.107.101:8000/api
                     │
┌────────────────────▼────────────────────────────┐
│ Laravel 12 - REST API Server                    │
│ - Sanctum token authentication                  │
│ - Eloquent ORM with model relationships         │
│ - Resource controllers for CRUD operations      │
│ - Role-based access control (student, staff)    │
└─────────────────────────────────────────────────┘
```

### Frontend Navigation Architecture (React)

- **Root:** `app/_layout.js` configures Stack navigator with three route groups
- **Login Screen:** `app/index.js` → authenticates with `/api/login`
- **Main Tabs:** `app/(tabs)/_layout.js` → registration, grades, home, modules, profile
- **Settings Stack:** `app/(settings)/*` → password change, profile edit
- **Modules Stack:** `app/(modules)/*` → enrollment, LOA, graduation forms (NOT part of main tab navigation)
- **Route groups** (parentheses like `(tabs)`, `(modules)`) organize screens logically without creating URL segments

### Backend API Structure (Laravel)

**Authentication:** Token-based via Laravel Sanctum
- `POST /api/login` → returns 200 on success (token handled by Sanctum middleware)
- Protected routes require `middleware('auth:sanctum')`

**Resource Routes (RESTful):**
- `apiResource('enrollments', EnrollmentController::class)` → GET/POST/PATCH/DELETE `/api/enrollments[/{id}]`
- `apiResource('courses', CourseController::class)` → courses, colleges, departments, semesters, registrations, grades
- Custom endpoints under `/api/prereg/*` → getActiveSemester, getAllSubjectsOffered, getSubjectsOffered, getPreregisteredSubjects

**Core Models & Relationships:**
- `User` (tbl_users) ↔ `Profile` (tbl_profile) — one-to-many via profile_id
- `Enrollment` (tbl_enrollments) — joins User/Semester/Course; fillable: [user_id, semester_id, course_id, section]
- `Course` (tbl_course) ↔ `Department` (tbl_department) — department_id foreign key
- `Registration` (tbl_registrations) — pre-registration state before enrollment
- `Semester` (tbl_semester) — tracks academic periods; active semester determines course offerings

---

## Critical Developer Workflows

### Backend (Laravel)

**Setup:**
```bash
cd cais-test-app
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
npm install && npm run build
```

**Development Server:**
```bash
composer run dev
# Concurrently runs: php artisan serve, queue:listen, pail (logging), Vite bundler
```

**Testing:**
```bash
composer run test
# Runs PHPUnit with @php artisan config:clear before each run
```

**Database Validation:**
- Check table/column names against `admissions.sql` (schema source of truth)
- Validation rules in `EnrollmentRequest.php` must match actual table structure
- Example: `exists:tbl_users,user_id` references column `user_id` in table `tbl_users`

### Frontend (React Native/Expo)

**Development:**
```bash
cd React
npm start
npm run android  # or ios / web
```

**Build Styling:**
- NativeWind converts Tailwind classNames to React Native StyleSheet
- Metro config processes CSS; Babel includes NativeWind JSX transformer
- Global styles in `global.css`; custom font weights loaded in `app/_layout.js`

---

## Project-Specific Conventions

### 1. Two-Step Registration Workflow (Most Critical)

**File:** `React/app/(modules)/prereg.js`

**Pattern:** Prevent accidental bulk submissions with local staging → confirmation → submission

- **Step 1: ADD Button (Bottom "LIST OF SUBJECTS OFFERED" table)**
  - Adds course to React state `locallyAddedSubjects` (NO API call)
  - Shows confirmation alert: "Course added to your preregistration list. Review and click REGISTER to submit."

- **Step 2: REGISTER Buttons (Top "YOUR PREREGISTERED COURSES" table)**
  - Locally added courses show in yellow background
  - Each course has individual REGISTER button OR single "REGISTER ALL" button
  - POSTs to `/api/enrollments` with [user_id, semester_id, course_id, section]
  - On success: moves to green section (already submitted)
  - Shows loading state: "SUBMITTING..." / "REGISTERING..."

**UI State Management:**
```javascript
const [locallyAddedSubjects, setLocallyAddedSubjects] = useState([]);
const [registering, setRegistering] = useState({});  // tracks per-course or 'all' key
const [enrolledSubjects, setEnrolledSubjects] = useState([]);  // server-confirmed
```

### 2. API Endpoint Patterns

**Hardcoded base URL:** `http://192.168.107.101:8000/api` (all React files)
- ⚠️ Development only—production requires environment variables
- Load from `constants.js` or `.env` equivalent if available

**Enrollment POST payload:**
```javascript
{
  user_id: 1033,
  semester_id: 11,
  course_id: 101,
  section: "BSIT_4-2"
}
```

**Error handling pattern (Axios + Alert):**
```javascript
try {
  const response = await axios.post(`${API_URL}/enrollments`, data);
} catch (error) {
  console.error(error);
  Alert.alert('Error', 'Failed to register course');
}
```

### 3. Styling & Font System

**Tailwind + NativeWind:**
- All className attributes converted to React Native styles
- Example: `className="flex-1 flex-row items-center"` → `{flex: 1, flexDirection: 'row', alignItems: 'center'}`

**Color Palette:**
- Primary Green: `#0a5419` (tab bar, accents)
- Accent Gold: `#ffd700` (active states)
- Gradient: `["#8ddd9eff", "#11581bff"]` (common background)

**Font Usage:**
- Montserrat (18 weights loaded via useFonts in root layout)
- Reference: `fontFamily: 'Montserrat-Bold'` or className `font-montserrat-bold`
- Weights available: Thin, ExtraLight, Light, Regular, Medium, SemiBold, Bold, ExtraBold, Black (and italic variants)

### 4. Component Patterns

**LinearGradient for visual hierarchy:**
```javascript
<LinearGradient colors={["#8ddd9eff", "#11581bff"]} className="flex-1">
  {/* Content */}
</LinearGradient>
```

**Table layouts (avoid FlatList for static data):**
```javascript
<View className="flex-row">
  <Text className="flex-[2] border border-gray-300">Column 1</Text>
  <Text className="flex-[1] border border-gray-300">Column 2</Text>
</View>
```

**Loading states:**
- Use `ActivityIndicator` from React Native
- Manage with component state: `const [loading, setLoading] = useState(false)`

### 5. Data Persistence Pattern

**AsyncStorage usage (emerging pattern):**
- Store user_id, semester_id, auth tokens for cross-screen access
- Example: `await AsyncStorage.getItem('user_id')` in enrollment.js
- Used in: enrollment.js, possibly other module screens
- ⚠️ No persistent login token yet—sessions likely reset on app restart

---

## Key Files & Directory Structure

### Backend (Laravel)

| Path | Purpose |
|------|---------|
| `app/Http/Controllers/*Controller.php` | RESTful endpoints for each resource |
| `app/Http/Requests/EnrollmentRequest.php` | Validation rules (verify against DB schema) |
| `app/Models/*.php` | Eloquent models with relationships |
| `routes/api.php` | Route definitions; authentication via Sanctum |
| `database/migrations/*.php` | Schema definitions (source of truth for validation) |
| `config/database.php` | DB connection config |

### Frontend (React)

| Path | Purpose |
|------|---------|
| `app/_layout.js` | Root layout, font loading, Stack navigator setup |
| `app/(tabs)/_layout.js` | Bottom tab navigation structure |
| `app/(modules)/prereg.js` | **Critical:** Two-step course registration |
| `app/(modules)/enrollment.js` | Enrollment form (less complete) |
| `app/(tabs)/home.js` | Main dashboard; pattern for gradient backgrounds |
| `app/index.js` | Login screen; defines API_URL constant |
| `tailwind.config.js` | Custom font weights, color definitions |
| `global.css` | Tailwind directives |
| `metro.config.js` | CSS processing pipeline |

---

## Known Limitations & TODOs

1. **Hardcoded API endpoint:** `192.168.107.101:8000` must be parameterized for production
2. **Auth token persistence:** No token storage between sessions (AsyncStorage ready but unused)
3. **Error boundaries:** No global error handling—local try/catch only
4. **Module screens incomplete:** Several forms defined in `app/(modules)/` may lack full implementations
5. **Database validation:** Validate all Eloquent relationships match actual schema (verify against admissions.sql)

---

## Integration Points & Data Flow

### Preregistration (Most Critical Feature)

```
User Login (app/index.js)
  ↓ POST /api/login
  ↓ Navigate to /home (app/(tabs)/home.js)
  ↓ Tap "Pre-Registration" → Push to /prereg (app/(modules)/prereg.js)
  ↓ Fetch /api/prereg/all-subjects (bottom table)
  ↓ Click ADD (local state update)
  ↓ Click REGISTER (POST /api/enrollments × N courses)
  ↓ Fetch /api/enrollments (refresh top table with server data)
```

### Cross-Component Dependencies

- **enrollment.js:** Uses AsyncStorage to get user_id, semester_id (set elsewhere or hardcoded)
- **Semester selection:** Not visible in UI—hardcoded fallback to semester 11
- **User profile:** Retrieved via authenticated `/api/user` endpoint (unused in current flow)
