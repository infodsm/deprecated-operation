#include <stdio.h>
#include <string.h>
#define MAX_LEN 20

int main() {
    int i = 0;
    int length = 0;
    char str[MAX_LEN] = {0};
    char after[MAX_LEN] = {0};

    printf("문자열 입력 >> ");
    scanf("%s", str);
    length = strlen(str);

    for (i = 0; i < length; i++) {
        after[i] = str[((length - i) - 1)];
    }
    after[length] = '\0';

    printf("%s\n", after);
    return 0;
}
