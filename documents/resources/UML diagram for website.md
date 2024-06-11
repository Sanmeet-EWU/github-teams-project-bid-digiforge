
``` plantuml
class User {
    +viewLandingPage(): void
    +viewProjectPage(): void
    +viewProjectDetails(): void
    +subscribeToNewsletter(): void
    +contact(): void
    +viewCalendarPage(): void
}

class Admin {
    +username: string
    +password: string
    +updateProjects(): void
    +sendNewsletter(): void
}

class Project {
    +title: string
    +description: string
    +images: Image[]
    +addImage(image: Image): void
}

class Image {
    +url: string
}

class Newsletter {
    +title: string
    +content: string
    +send(): void
}

class NewsletterWidget {
    +display(): void
}

class ContactForm {
    +name: string
    +email: string
    +message: string
    +submit(): void
}

class CalendarPage {
    +display(): void
}

class WordPressBackend {
    +login(username: string, password: string): void
    +updateContent(): void
}

class FigmaDesign {
    +createDesign(): void
}

class GitHub {
    +commitChanges(): void
    +pushChanges(): void
    +pullChanges(): void
}

class LandingPage {
    +display(): void
}

class ProjectPage {
    +display(): void
}

class ContactPage {
    +display(): void
}

User "1" --> "1" LandingPage
User "1" --> "1" ProjectPage
User "1" --> "1" Project
User "1" --> "1" Newsletter
User "1" --> "1" NewsletterWidget
User "1" --> "1" ContactForm
User "1" --> "1" CalendarPage

LandingPage "1" --> "1" ProjectPage
ProjectPage "1" --> "1" ProjectDetails
Newsletter "1" --> "1" WordPressBackend
NewsletterWidget "1" --> "1" Newsletter
CalendarPage "1" --> "1" WordPressBackend

ContactForm "1" --> "1" WordPressBackend

WordPressBackend "1" --> "1" FigmaDesign
WordPressBackend "1" --> "1" GitHub
```
