# 🤝 CONTRIBUTING TO UIGM POLBAN SYSTEM

Thank you for your interest in contributing to the UIGM POLBAN System! This document provides guidelines and information for contributors.

---

## 📋 Table of Contents

- [Code of Conduct](#-code-of-conduct)
- [Getting Started](#-getting-started)
- [Development Setup](#-development-setup)
- [Contributing Guidelines](#-contributing-guidelines)
- [Pull Request Process](#-pull-request-process)
- [Coding Standards](#-coding-standards)
- [Testing Requirements](#-testing-requirements)
- [Documentation](#-documentation)
- [Issue Reporting](#-issue-reporting)
- [Security Issues](#-security-issues)

---

## 📜 Code of Conduct

### Our Pledge
We are committed to providing a welcoming and inclusive environment for all contributors, regardless of background, experience level, or identity.

### Expected Behavior
- Use welcoming and inclusive language
- Be respectful of differing viewpoints and experiences
- Gracefully accept constructive criticism
- Focus on what is best for the community
- Show empathy towards other community members

### Unacceptable Behavior
- Harassment, discrimination, or offensive comments
- Personal attacks or trolling
- Publishing private information without permission
- Any conduct that would be inappropriate in a professional setting

---

## 🚀 Getting Started

### Prerequisites
Before contributing, ensure you have:
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer
- Git
- Basic knowledge of CodeIgniter 4
- Understanding of MVC architecture

### First-Time Contributors
1. **Fork the repository** on GitHub
2. **Clone your fork** locally
3. **Set up the development environment** (see below)
4. **Create a feature branch** for your contribution
5. **Make your changes** following our guidelines
6. **Test your changes** thoroughly
7. **Submit a pull request**

---

## 🔧 Development Setup

### 1. Clone and Install
```bash
# Clone your fork
git clone https://github.com/YOUR-USERNAME/uigm-system.git
cd uigm-system

# Add upstream remote
git remote add upstream https://github.com/polban/uigm-system.git

# Install dependencies
composer install
```

### 2. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Configure database settings in .env
# Set CI_ENVIRONMENT = development
```

### 3. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE uigm_polban_dev;
EXIT;

# Import database
mysql -u root -p uigm_polban_dev < backup/sql/database_backup_clean_fixed.sql
```

### 4. Start Development Server
```bash
php spark serve --host=localhost --port=8080
```

### 5. Verify Installation
```bash
# Run system verification
php scripts/maintenance/system_verification.php

# Test authentication
php scripts/testing/test_authentication.php
```

---

## 📝 Contributing Guidelines

### Types of Contributions

#### 🐛 Bug Fixes
- Fix existing functionality that isn't working as expected
- Include test cases that reproduce the bug
- Update documentation if necessary

#### ✨ New Features
- Add new functionality that enhances the system
- Follow existing patterns and conventions
- Include comprehensive tests
- Update documentation and examples

#### 📚 Documentation
- Improve existing documentation
- Add missing documentation
- Fix typos and grammatical errors
- Add code examples and tutorials

#### 🎨 UI/UX Improvements
- Enhance user interface design
- Improve user experience
- Ensure mobile responsiveness
- Maintain accessibility standards

#### ⚡ Performance Improvements
- Optimize database queries
- Improve page load times
- Reduce memory usage
- Enhance caching strategies

### Contribution Workflow

1. **Check existing issues** to avoid duplicate work
2. **Create an issue** for major changes to discuss approach
3. **Fork and branch** from the main branch
4. **Make focused commits** with clear messages
5. **Write or update tests** for your changes
6. **Update documentation** as needed
7. **Submit a pull request** with detailed description

---

## 🔄 Pull Request Process

### Before Submitting
- [ ] Code follows project coding standards
- [ ] All tests pass
- [ ] Documentation is updated
- [ ] Commit messages are clear and descriptive
- [ ] No merge conflicts with main branch

### Pull Request Template
```markdown
## Description
Brief description of changes made.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Documentation update
- [ ] Performance improvement
- [ ] Other (please describe)

## Testing
- [ ] Unit tests added/updated
- [ ] Integration tests pass
- [ ] Manual testing completed

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No breaking changes (or documented)
```

### Review Process
1. **Automated checks** must pass (tests, linting)
2. **Code review** by maintainers
3. **Testing** in development environment
4. **Documentation review** if applicable
5. **Approval** and merge by maintainers

---

## 📏 Coding Standards

### PHP Standards
Follow **PSR-12** coding standards:

```php
<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ExampleController extends Controller
{
    public function index(): string
    {
        $data = [
            'title' => 'Example Page',
            'content' => 'This is an example.'
        ];
        
        return view('example/index', $data);
    }
    
    private function validateInput(array $input): bool
    {
        // Validation logic here
        return true;
    }
}
```

### Database Conventions
```php
// Table names: lowercase with underscores
CREATE TABLE user_profiles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

// Model naming: PascalCase with "Model" suffix
class UserProfileModel extends Model
{
    protected $table = 'user_profiles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'profile_data'];
}
```

### Frontend Standards
```javascript
// Use modern JavaScript (ES6+)
const fetchUserData = async (userId) => {
    try {
        const response = await fetch(`/api/users/${userId}`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching user data:', error);
        throw error;
    }
};

// CSS: Use BEM methodology
.user-profile {
    padding: 1rem;
}

.user-profile__header {
    font-size: 1.5rem;
    font-weight: bold;
}

.user-profile__content--highlighted {
    background-color: #f8f9fa;
}
```

### Documentation Standards
```php
/**
 * Retrieves user profile information
 *
 * @param int $userId The user ID to retrieve profile for
 * @param array $options Additional options for profile retrieval
 * @return array User profile data
 * @throws UserNotFoundException When user is not found
 */
public function getUserProfile(int $userId, array $options = []): array
{
    // Implementation here
}
```

---

## 🧪 Testing Requirements

### Unit Tests
```php
<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\UserModel;

class UserModelTest extends CIUnitTestCase
{
    public function testCreateUser()
    {
        $userModel = new UserModel();
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];
        
        $userId = $userModel->insert($userData);
        $this->assertIsNumeric($userId);
        
        $user = $userModel->find($userId);
        $this->assertEquals('testuser', $user['username']);
    }
}
```

### Integration Tests
```php
<?php

namespace Tests\Integration;

use CodeIgniter\Test\FeatureTestCase;

class AuthenticationTest extends FeatureTestCase
{
    public function testLoginFlow()
    {
        $result = $this->post('/auth/login', [
            'username' => 'adminpusat',
            'password' => 'password123'
        ]);
        
        $result->assertRedirectTo('/admin-pusat/dashboard');
        $this->assertTrue(session()->get('isLoggedIn'));
    }
}
```

### Running Tests
```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite Unit

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/
```

---

## 📚 Documentation

### Code Documentation
- Use PHPDoc for all public methods
- Include parameter types and return types
- Document complex algorithms and business logic
- Add inline comments for non-obvious code

### User Documentation
- Update README.md for new features
- Add examples and usage instructions
- Include screenshots for UI changes
- Update API documentation

### Commit Messages
Follow conventional commit format:
```
type(scope): description

[optional body]

[optional footer]
```

Examples:
```
feat(auth): add two-factor authentication
fix(dashboard): resolve chart rendering issue
docs(api): update endpoint documentation
test(user): add user registration tests
```

---

## 🐛 Issue Reporting

### Bug Reports
Use the bug report template:
```markdown
**Bug Description**
Clear description of the bug.

**Steps to Reproduce**
1. Go to '...'
2. Click on '...'
3. See error

**Expected Behavior**
What should happen.

**Actual Behavior**
What actually happens.

**Environment**
- OS: [e.g., Windows 10]
- Browser: [e.g., Chrome 96]
- PHP Version: [e.g., 8.2]
- Database: [e.g., MySQL 8.0]

**Screenshots**
If applicable, add screenshots.

**Additional Context**
Any other relevant information.
```

### Feature Requests
```markdown
**Feature Description**
Clear description of the proposed feature.

**Use Case**
Why is this feature needed?

**Proposed Solution**
How should this feature work?

**Alternatives Considered**
Other approaches you've considered.

**Additional Context**
Any other relevant information.
```

---

## 🔒 Security Issues

### Reporting Security Vulnerabilities
**DO NOT** create public issues for security vulnerabilities.

Instead:
1. **Email**: security@polban.ac.id
2. **Include**: Detailed description and steps to reproduce
3. **Wait**: For acknowledgment before public disclosure
4. **Coordinate**: Responsible disclosure timeline

### Security Guidelines
- Never commit sensitive data (passwords, API keys)
- Use environment variables for configuration
- Validate and sanitize all user inputs
- Follow OWASP security guidelines
- Keep dependencies updated

---

## 🏆 Recognition

### Contributors
All contributors will be recognized in:
- CHANGELOG.md
- README.md contributors section
- Release notes
- Project documentation

### Contribution Types
We recognize various types of contributions:
- 💻 Code
- 📖 Documentation
- 🐛 Bug reports
- 💡 Ideas
- 🎨 Design
- 🧪 Testing
- 🌍 Translation
- 📢 Outreach

---

## 📞 Getting Help

### Communication Channels
- **GitHub Issues**: For bugs and feature requests
- **Email**: dev@polban.ac.id for development questions
- **Documentation**: Check docs/ folder first

### Response Times
- **Bug reports**: Within 48 hours
- **Feature requests**: Within 1 week
- **Pull requests**: Within 1 week
- **Security issues**: Within 24 hours

---

## 📄 License

By contributing to this project, you agree that your contributions will be licensed under the MIT License. See [LICENSE](LICENSE) file for details.

---

**Thank you for contributing to the UIGM POLBAN System! 🙏**

*Together, we're building a better sustainability tracking system for educational institutions.*