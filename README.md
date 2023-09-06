# PrzepisowoPHP
Webapp w php

Co jest:
- wykorzystanie Framework'a Symfony
- projekt odpala się na serwerze lokalnym
- projekt posiada bazę danych z 5 encjami (nie dało się sensownie więcej)
- projekt umożliwia logowanie, dodawanie, edycję, usuwanie, wyświetlenie listy z (prawie wszystkimi) obiektami
- projekt oparty na twigach
- architektura MVC
- wykorzystanie jednej tabeli dynamicznej w formularzu (tzn. że do tej tabeli dynamicznej można dodawać, usuwać wiersze i przy zapisie dane dodają się odpowiednio do encji) (zaimplementowane przy pomocy biblioteki js)

ponadto wykorzystałem Tailwind oraz DaisyUI do zbudowania interfejsu

Czego nie ma:

- testów jednostkowych przy pomocy PHP Unit (dodawania elementów, usuwania elementów, edycję)
- integracja z jednym zewnętrznym API (dowolnie wybranym) oraz umiejscowienie informacji w projekcie (np. umieszczenie pogody)
wykorzystanie API Platform
- walidacja wszystkich pól przy dodawaniu, edytowaniu
- logowanie dwuskładnikowe
- rzucanie wyjątków w logice (np. przy braku możliwości znalezienia obiektu)
- obsługa języka polskiego i angielskiego
