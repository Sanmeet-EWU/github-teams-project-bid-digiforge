
``` mermaid 
classDiagram
    class User {
        viewLandingPage()
        viewProjectPage()
        viewProjectDetails()
        subscribeToNewsletter()
        contact()
        viewCalendarPage()
    }

    class Admin {
        username: string
        password: string
        updateProjects()
        sendNewsletter()
    }

    class Project {
        title: string
        description: string
        images: Image[]
        addImage(image: Image)
    }

    class Image {
        url: string
    }

    class Newsletter {
        title: string
        content: string
        send()
    }

    class NewsletterWidget {
        display()
    }

    class ContactForm {
        name: string
        email: string
        message: string
        submit()
    }

    class CalendarPage {
        display()
    }

    class WordPressBackend {
        login(username: string, password: string)
        updateContent()
    }

    class FigmaDesign {
        createDesign()
    }

    class GitHub {
        commitChanges()
        pushChanges()
        pullChanges()
    }

    class LandingPage {
        display()
    }

    class ProjectPage {
        display()
    }

    class ContactPage {
        display()
    }

    User --> LandingPage
    User --> ProjectPage
    User --> Project
    User --> Newsletter
    User --> NewsletterWidget
    User --> ContactForm
    User --> CalendarPage

    LandingPage --> ProjectPage
    ProjectPage --> ProjectDetails
    Newsletter --> WordPressBackend
    NewsletterWidget --> Newsletter
    CalendarPage --> WordPressBackend

    ContactForm --> WordPressBackend

    WordPressBackend --> FigmaDesign
    WordPressBackend --> GitHub

```
