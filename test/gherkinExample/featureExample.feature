Scenario Outline: Eating
  Given there are <start> cucumbers
  When I eat <eat> cucumbers
  Then I should have <left> cucumbers

  Examples:
    | start | eat | left |
    |  12   |  5  |  7   |
    |  20   |  5  |  15  |

Scenario Outline: Drinking
  Given there are <start> cucumber smoothies
  When I drink <drink> cucumber smoothies
  Then I should have <left> cucumber smoothies

  Examples:
    | start | drink | left |
    |  13   |  5  |  8   |
    |  21   |  5  |  16  |