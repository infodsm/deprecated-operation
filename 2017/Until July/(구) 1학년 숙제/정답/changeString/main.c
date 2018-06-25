#include <stdio.h>
#include <string.h>
#define MAX_LEN 20

int main() {
    int i = 0;
    char *p = NULL;
    char str[MAX_LEN] = {0};
    char before[MAX_LEN] = {0};
    char after[MAX_LEN] = {0};

    printf("문자열 입력 >> ");
    scanf("%s %s %s", str, before, after);

    while ((p = strstr(str, before)) != NULL) {
        for (i = 0; i < strlen(after); i++) {
            *(p + i) = after[i];
        }
    }

    printf("%s\n", str);
    return 0;
}
