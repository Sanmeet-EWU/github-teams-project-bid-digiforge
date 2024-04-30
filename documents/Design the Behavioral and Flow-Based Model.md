
## Activity Diagram 

``` mermaid
graph TD
    Start[Start] --> LandingPage
    subgraph Main Flow
    LandingPage --> ProjectPage
    ProjectPage --> ProjectDetails
    ProjectDetails --> ProjectPage
    ProjectPage --> ContactForm
    ProjectPage --> NewsletterWidget
    ContactForm --> WordPressBackend
    NewsletterWidget --> Newsletter
    Newsletter --> WordPressBackend
    WordPressBackend --> FigmaDesign
    WordPressBackend --> GitHub
    end
    subgraph Optional Flow
    ProjectPage --> CalendarPage
    CalendarPage --> WordPressBackend
    end
    WordPressBackend --> End[End]

```

## Interaction Diagram (Sequence Diagram):

``` mermaid
sequenceDiagram
    participant User
    participant LandingPage
    participant ProjectPage
    participant ContactForm
    participant NewsletterWidget
    participant Newsletter
    participant WordPressBackend
    participant FigmaDesign
    participant GitHub
    
    User->>LandingPage: viewLandingPage()
    User->>ProjectPage: viewProjectPage()
    ProjectPage->>ContactForm: contact()
    ProjectPage->>NewsletterWidget: subscribeToNewsletter()
    ContactForm->>WordPressBackend: submit()
    NewsletterWidget->>Newsletter: send()
    Newsletter->>WordPressBackend: send()
    WordPressBackend->>FigmaDesign: updateContent()
    WordPressBackend->>GitHub: commitChanges()

```

## State Chart Diagram:

```mermaid
stateDiagram
    [*] --> LandingPage
    LandingPage --> ProjectPage: viewProjectPage()
    ProjectPage --> ProjectDetails: viewProjectDetails()
    ProjectDetails --> ProjectPage
    ProjectPage --> ContactForm: contact()
    ProjectPage --> NewsletterWidget: subscribeToNewsletter()
    NewsletterWidget --> Newsletter: send()
    ContactForm --> WordPressBackend: submit()
    Newsletter --> WordPressBackend: send()
    WordPressBackend --> FigmaDesign: updateContent()
    WordPressBackend --> GitHub: commitChanges()
    ProjectPage --> CalendarPage: viewCalendarPage()
    CalendarPage --> WordPressBackend
    FigmaDesign --> WordPressBackend: contentUpdated()
    GitHub --> WordPressBackend: changesCommitted()

```