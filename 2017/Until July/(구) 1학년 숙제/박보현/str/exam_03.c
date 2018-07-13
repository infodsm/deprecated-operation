#include <stdio.h>

char strcat(char *a, char *b)
{
	int i = 0, j = 0;

	while (a[i] != '\0')
	{
		i++;
	}

	while (b[j] != '\0')
	{
		a[i++] = b[j++];
	}
}

int exam_03()
{
	char a[50] = "BB ", b[] = "puzzle ";

	strcat(a, b);

	printf("%s\n", a);
}